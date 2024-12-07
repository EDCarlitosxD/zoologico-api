<?php 
namespace App\Services;

use App\Models\Donacion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DonacionService{

public function guardardatos($request, $id, $email, $nombre, $fecha){
    $datos = $request->all();

    $validar = Validator::make($datos, [
        'monto' => 'required|numeric',
    ]);

    if($validar->fails()){
        throw ValidationException::withMessages([
            "message" => "Validacion incorrecta"
        ]);
    }

    Donacion::create([
        'id_usuario' => $id,
        'monto' => $datos['monto'],
        'email' => $email,
        'fecha' => $fecha
    ]);

    $datosrecibo = [
        'nombre_usuario' => $nombre,
        'monto' => $datos['monto']
    ];

    return $datosrecibo;



}

}

?>