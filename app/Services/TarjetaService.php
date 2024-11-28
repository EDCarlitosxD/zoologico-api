<?php 
namespace App\Services;

use App\Models\Tarjeta;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TarjetaService{

    public function procesarTarjeta($request, $idusuario){
        $datos = $request->all();

        $validar = Validator::make($datos,[
            'fecha_expiracion' => 'required|max:255',
            'banco' => 'required|max:255',
            'numero_tarjeta' => 'required|max:255|numeric',
            'nombre_tarjeta' => 'required|max:255',
            'ccv' => 'required|max:5|numeric',
            'tipo_tarjeta' => 'required|max:255',
        ]);

        if($validar->fails()){
            throw ValidationException::withMessages([
                "message" => "validacion incorrecta"
            ]);
        }

        Tarjeta::create([
            'fecha_expiracion' => $datos['fecha_expiracion'],
            'banco' => $datos['banco'],
            'numero_tarjeta' => $datos['numero_tarjeta'],
            'nombre_tarjeta' => $datos['nombre_tarjeta'],
            'ccv' => $datos['ccv'],
            'tipo_tarjeta' => $datos['tipo_tarjeta'],
            'id_usuario' => $idusuario
        ]);

        return response()->json(['message' => 'Tarjeta agregada con exito']);
    }

    public function eliminarTarjeta($id){
        $tarjeta = Tarjeta::find($id);

        $tarjeta->delete();

        return response()->json(['message' => 'Tarjeta eliminada con exito']);
    }


}





?>