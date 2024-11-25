<?php 
namespace App\Services;

use App\Models\Donacion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DonacionService{

public function guardardatos($request, $id, $email, $nombre){
    $datos = $request->all();

    $validar = Validator::make($datos, [
        'monto' => 'required',
        'mensaje' => 'required'
    ]);

    if($validar->fails()){
        throw ValidationException::withMessages([
            "message" => "Validacion incorrecta"
        ]);
    }

    Donacion::create([
        'id_usuario' => $id,
        'monto' => $datos['monto'],
        'mensaje' => $datos['mensaje'],
        'email' => $email
    ]);

    $datosrecibo = [
        'nombre_usuario' => $nombre,
        'monto' => $datos['monto']
    ];

    return $datosrecibo;



}




}


?>