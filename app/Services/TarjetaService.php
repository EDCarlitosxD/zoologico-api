<?php
namespace App\Services;

use App\Models\Tarjeta;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TarjetaService{

    public function procesarTarjeta($request, $idusuario){
        $datos = $request->all();

        $request->validate([
            'fecha_expiracion' => 'required|max:255',
            'banco' => 'required|max:255',
            'numero_tarjeta' => 'required',
            'nombre_tarjeta' => 'required|max:255',
            'ccv' => 'required|max:5',
            'tipo_tarjeta' => 'required|max:255',
        ]);

        // if($validar->fails()){
        //     throw ValidationException::withMessages([
        //         "message" => "validacion incorrecta"
        //     ]);
        // }

        $tarjeta = Tarjeta::create([
            'fecha_expiracion' => $datos['fecha_expiracion'],
            'banco' => $datos['banco'],
            'numero_tarjeta' => $datos['numero_tarjeta'],
            'nombre_tarjeta' => $datos['nombre_tarjeta'],
            'ccv' => $datos['ccv'],
            'tipo_tarjeta' => $datos['tipo_tarjeta'],
            'id_usuario' => $idusuario
        ]);

        return response()->json($tarjeta);
    }

    // public function eliminarTarjeta($id){
    //     $tarjeta = Tarjeta::find($id);

    //     $tarjeta->delete();

    //     return response()->json(['message' => 'Tarjeta eliminada con exito']);
    // }
    public function eliminarTarjeta($id){

        $tarjeta = Tarjeta::findOrFail($id);
        $tarjeta->estado = 0;
        $tarjeta->save();

        return response()->json(['message' => 'Tarjeta estado actualizado con éxito']);
    }

}





?>
