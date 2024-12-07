<?php
namespace App\Services;

use App\Models\Boletos;
use App\Models\HorarioRecorrido;
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

            $precio = Boletos::findOrFail($dato['id_boleto'])->precio;
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
                throw ValidationException::withMessages($validar->errors()->toArray());
            }

            Reserva::create([
                'id_usuario' => $id_usuario,
                'cantidad_personas' => $dato['cantidad_personas'],
                'id_horario_recorrido' => $dato['id_horario_recorrido'],
                'token' => $token,
                'fecha' => $fechaactual
            ]);

            $tiporecorrido = Recorrido::select('titulo')->where('id', $dato['id_recorrido'])->first();
            $horarioRecorrido = HorarioRecorrido::where('id', $dato['id_horario_recorrido'])->first();
            $precio = Recorrido::findOrFail($dato['id_recorrido'])->precio;

            $recorridos [] = [
                'tiporecorrido' => $tiporecorrido->titulo,
                'cantidad_personas' => $dato['cantidad_personas'],
                'precio' => $precio,
                'token' => $token,
                'hora_inicio' => $horarioRecorrido->horario_inicio,
                'hora_fin' => $horarioRecorrido->horario_fin,
                'fecha' => $horarioRecorrido->fecha
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
