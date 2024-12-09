<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\MensajeUsuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class FormularioContactoController
{
    public function mensajeusuario(Request $request){
        $dato = $request->all();
        $emailzoologic = 'contactozoologic@gmail.com';

        $validar = Validator::make($dato, [
            "nombre" => 'required|max:100',
            "telefono" => 'required|max:10',
            "email" => 'required|max:70',
            "mensaje" => 'required|max:400'
        ]);

        if($validar->fails()){
            throw ValidationException::withMessages(["message" => "validacion incorrecta"]);
        }

        $datos = [
            "nombre" => $dato['nombre'],
            "telefono" => $dato['telefono'],
            "email" => $dato['email'],
            "mensaje" => $dato['mensaje']
        ];

        Mail::to($emailzoologic)->send(new MensajeUsuario($datos));
        
        return response()->json(["message" => "Mensaje enviado con exito"]);

    }
}
