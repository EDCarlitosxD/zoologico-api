<?php
namespace App\Services;

use App\Models\Boletos;
use App\Models\Recorrido;
use App\Models\Reserva;
use App\Models\venta_boletos;
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
                throw ValidationException::withMessages([
                    "message" => "Validación incorrecta"
                ]);
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

    
    public function reservarRecorrido($request, $id_usuario, $token, $fechaactual){
        $datos = $request->all();
        $recorridos= [];


        foreach ($datos['recorridos'] as $dato){
            $validar = Validator::make($dato, [
                'id_recorrido',
                'cantidad_personas' => 'required|integer',
                'id_horario_recorrido' => 'required'
            ]);

            if($validar->fails()){
                throw ValidationException::withMessages(['message' => 'La validacion fallo']);
            }

            $precio = Recorrido::findOrFail($dato['id_recorrido'])->precio;
            $precio_total = $precio * ($dato['cantidad_personas']);

            Reserva::create([
                'id_usuario' => $id_usuario,
                'cantidad_personas' => $dato['cantidad_personas'],
                'precio_total' => $precio_total,
                'id_horario_recorrido' => $dato['id_horario_recorrido'],
                'token' => $token,
                'fecha' => $fechaactual
            ]);

            $tiporecorrido = Recorrido::select('titulo')->where('id', $dato['id_recorrido'])->first();

            $recorridos [] = [
                'tiporecorrido' => $tiporecorrido->titulo,
                'cantidad_personas' => $dato['cantidad_personas'],
                'precio' => $precio,
                'token' => $token,
            ];
        }

        return $recorridos;

    }

    public function calcularTotal($request)
    {
        $datos = $request->all();
        $totalboletos = 0;
        $totalrecorridos = 0;
        foreach ($datos['boletos'] as $dato) {
            $precio = Boletos::findOrFail($dato['id_boleto'])->precio;
            $totalboletos += $dato['cantidad'] * $precio;
        }

        foreach ($datos['recorridos'] as $dato) {
            $precio = Recorrido::findOrFail($dato['id_recorrido'])->precio;
            $totalrecorridos += $dato['cantidad_personas'] * $precio;
        }

        $totalcompra = $totalboletos+$totalrecorridos;

        return $totalcompra;
        
    }


}



?>