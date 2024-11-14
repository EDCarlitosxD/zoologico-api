<?php
namespace App\Services;

use App\Models\Boletos;
use App\Models\venta_boletos;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class VentaService
{
    public function procesarVenta($request, $token, $fechaactual, $email)
    {
        $boletos = [];
        $datos = $request->all();

        foreach ($datos as $dato) {
            $validar = Validator::make($dato, [
                'id_usuario' => 'required',
                'id_boleto' => 'required',
                'cantidad' => 'required|integer',
            ]);

            if ($validar->fails()) {
                throw ValidationException::withMessages([
                    "message" => "Validación incorrecta"
                ]);
            }

            venta_boletos::create([
                'id_usuario' => $dato['id_usuario'],
                'id_boleto' => $dato['id_boleto'],
                'fecha' => $fechaactual,
                'cantidad' => $dato['cantidad'],
                'token' => $token,
                'email' => $email
            ]);

            $precio = Boletos::findOrFail($dato['id_boleto'])->precio;
            $tipoboleto = Boletos::select('titulo')->where('id', $dato['id_boleto'])->first();
            $boletos[] = [
                'tipoboleto' => $tipoboleto->titulo,
                'cantidad' => $dato['cantidad'],
                'precio' => $precio
            ];
        }

        return $boletos;
    }

    public function calcularTotal($request)
    {
        $datos = $request->all();
        $total = 0;
        foreach ($datos as $dato) {
            $precio = Boletos::findOrFail($dato['id_boleto'])->precio;
            $total += $dato['cantidad'] * $precio;
        }
        return $total;
    }
}



?>