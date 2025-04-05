<?php
namespace App\Services;

use App\Models\Boletos;
use App\Models\HorarioRecorrido;
use App\Models\Recorrido;
use App\Models\Reserva;
use App\Models\venta_boletos;
use App\Models\MembresiasUsuarios;
use App\Models\VistaBoletosVendidosGeneral;
use App\Models\VistaBoletosVendidosMes;
use App\Models\VistaBoletosVendidosSemana;
use App\Models\VistaBoletosVendidosYear;
use App\Models\VistaVentasGeneral;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class VentaService
{
    public function procesarVenta($request, $token, $fechaactual, $email, $id_usuario)
    {

        $boletos = [];
        $datos = $request->all();

        foreach ($datos['boletos'] as $dato) {
            $validar = Validator::make($dato, [
                'id_boleto' => 'required',
                'cantidad' => 'required|integer',
            ]);

            if ($validar->fails()) {
                throw ValidationException::withMessages($validar->errors()->toArray());
            }

            $precio = Boletos::findOrFail($dato['id_boleto'])->precio;
            $precio_total = $precio * ($dato['cantidad']);

            venta_boletos::create([
                'id_usuario' => $id_usuario,
                'id_boleto' => $dato['id_boleto'],
                'fecha' => $fechaactual,
                'cantidad' => $dato['cantidad'],
                'token' => $token,
                'email' => $email,
                'precio_total' => $precio_total
            ]);

            $tipoboleto = Boletos::select('titulo')->where('id', $dato['id_boleto'])->first();

            $boletos[] = [
                'tipoboleto' => $tipoboleto->titulo,
                'cantidad' => $dato['cantidad'],
                'precio' => $precio,
                'token' => $token
            ];
        }

        return $boletos;
    }
    public function reservarRecorrido($request, $id_usuario, $token, $fechaactual)
{
    $recorridos = [];
    
    foreach ($request->recorridos as $recorrido) {
        // IMPORTANT: Use id_horario_recorrido from the request instead of id_recorrido
        $recorridoId = $recorrido['id_horario_recorrido'];
        
        // Obtener información del recorrido desde la base de datos
        $recorridoInfo = Recorrido::find($recorrido['id_recorrido']);
        
        if (!$recorridoInfo) {
            throw new \Exception("Recorrido no encontrado");
        }
        
        // Verificar si el precio con descuento viene del frontend
        $precio = $recorrido['precio'] ?? $recorridoInfo->precio; // Use null coalescing operator for safety
        $precioOriginal = $recorridoInfo->precio;
        $descuentoAplicado = null;
        
        // Si hay diferencia entre el precio del recorrido en DB y el precio enviado,
        // significa que se aplicó un descuento
        if ($precio && $precio < $precioOriginal) {
            $descuentoAplicado = [
                'precioOriginal' => $precioOriginal,
                'precioConDescuento' => $precio,
                'descuento' => $precioOriginal - $precio,
                'porcentajeDescuento' => isset($recorrido['descuentoAplicado']['porcentaje']) ? 
                    $recorrido['descuentoAplicado']['porcentaje'] : null,
                'tipoMembresia' => isset($recorrido['descuentoAplicado']['tipoMembresia']) ? 
                    $recorrido['descuentoAplicado']['tipoMembresia'] : null
            ];
        }
        
        // Calculate the final price, handling null price
        $finalPrice = $precio ?? 0;
        
        // Crear la reserva
        $reserva = new Reserva();
        $reserva->id_usuario = $id_usuario;
        $reserva->cantidad = $recorrido['cantidad'];
        $reserva->id_horario_recorrido = $recorridoId; // Use the correct ID from request
        $reserva->fecha = $fechaactual;
        $reserva->precio_total = $finalPrice * $recorrido['cantidad']; // Handle null price
        $reserva->estado = 1;
        $reserva->token = $token;
        $reserva->save();
        
        // Guardar información del descuento si se aplicó
        if ($descuentoAplicado) {
            DB::table('descuentos_aplicados')->insert([
                'id_reserva' => $reserva->id,
                'precio_original' => $descuentoAplicado['precioOriginal'],
                'precio_con_descuento' => $descuentoAplicado['precioConDescuento'],
                'valor_descuento' => $descuentoAplicado['descuento'],
                'porcentaje_descuento' => $descuentoAplicado['porcentajeDescuento'],
                'tipo_membresia' => $descuentoAplicado['tipoMembresia'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        // Agregar a la lista de recorridos procesados
        $recorridos[] = [
            'tiporecorrido' => $recorridoInfo->titulo,
            'id_recorrido' => $recorrido['id_recorrido'],
            'cantidad' => $recorrido['cantidad'],
            'precio' => $finalPrice, // Handle null price
            'precio_original' => $precioOriginal, // Guardar el precio original
            'descuento_aplicado' => $descuentoAplicado ? $descuentoAplicado['porcentajeDescuento'] : 0,
            'total' => $finalPrice * $recorrido['cantidad'],
            'token' => $token,
            'fecha' => $recorrido['fecha'] ?? $fechaactual,
            'hora_inicio' => $recorrido['hora_inicio'] ?? null,
            'hora_fin' => $recorrido['hora_fin'] ?? null
        ];
    }
    
    return $recorridos;
}

    // También deberás modificar el método calcularTotal para considerar los precios con descuento
    public function calcularTotal($request)
    {
        $total = 0;
        
        if ($request->has('boletos')) {
            foreach ($request->boletos as $boleto) {
                $precio = $boleto['precio'] ?? 0;
                $total += $precio * $boleto['cantidad'];
            }
        }
        
        if ($request->has('recorridos')) {
            foreach ($request->recorridos as $recorrido) {
                $precio = $recorrido['precio'] ?? 0;
                $total += $precio * $recorrido['cantidad'];
            }
        }
        
        return $total;
    }

    public function procesarVentaMembresia($request, $token, $fechaactual, $email){

        $datos = $request->all();
        $fecha = $fechaactual instanceof Carbon ? $fechaactual : Carbon::parse($fechaactual);
        $fecha_vencimiento = $fecha->copy()->addMonths($datos['meses']);
        $data = [
            'id_usuario' => $datos['id_usuario'],
            'id_membresia' => $datos['id_membresia'],
            'fecha_compra' => $fechaactual,
            'meses' => $datos['meses'],
            'fecha_vencimiento' => $fecha_vencimiento,
            'token' => $token,
            'email' => $email,
            'precio_total' => $datos['precio_total']
        ];
        MembresiasUsuarios::create($data);
        return $data; 
    }

    public function ventasGeneral(){
        $ventasGeneral = VistaVentasGeneral::all();
        return $ventasGeneral;
    }

    public function traerVentasGeneral(){
        $boletosVendidos = VistaBoletosVendidosGeneral::select('id','titulo', 'cantidad')->get()->groupBy('id');
        return $boletosVendidos;
    }

    public function bvendidosSemana(){


        $bsemana = VistaBoletosVendidosSemana::select('id', 'titulo', 'cantidad')->get()->groupBy('id');

        $cantidad = VistaBoletosVendidosSemana::sum('cantidad');

        $datos = [
            "VentaSemana" => $bsemana,
            "cantidad_total" => $cantidad
        ];

        return $datos;
    }

    public function bvendidosMes(){
        $bmes = VistaBoletosVendidosMes::select('id', 'titulo', 'cantidad')->get()->groupBy('id');
        $cantidad = VistaBoletosVendidosMes::sum('cantidad');

        $datos = [
            "VentaMes" => $bmes,
            "cantidad_total" => $cantidad
        ];

        return $datos;
    }

    public function bvendidosYear(){
        $byear = VistaBoletosVendidosYear::select('id', 'titulo', 'cantidad')->get()->groupBy('id');

        $cantidad = VistaBoletosVendidosYear::sum('cantidad');

        $datos = [
            "VentaYear" => $byear,
            "cantidad_total" => $cantidad
        ];

        return $datos;
    }


}



?>
