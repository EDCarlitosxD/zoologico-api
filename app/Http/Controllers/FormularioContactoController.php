<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\MensajeUsuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Contacto",
 *     description="API para gestionar el formulario de contacto"
 * )
 */
class FormularioContactoController
{

    /**
     * Envia el mensaje al correo de contacto
     * @OA\Post(
     *     path="/contacto",
     *     summary="Envia el mensaje al correo de contacto",
     *     description="Envia el mensaje al correo de contacto",
     *     tags={"Contacto"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Monto de la donación",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"nombre", "telefono", "email", "mensaje"},
     *                 @OA\Property(property="nombre", type="string", example="John Doe"),
     *                 @OA\Property(property="telefono", type="string", example="1234567890"),
     *                 @OA\Property(property="email", type="string", example="2VH2U@example.com"),
     *                 @OA\Property(property="mensaje", type="string", example="Hello, this is a test message."),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mensaje enviado con exito",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Mensaje enviado con exito")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al enviar el mensaje",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error al enviar el mensaje")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No autorizado")
     *         )
     *     )
     * )
     */

    public function mensajeusuario(Request $request){
        $dato = $request->all();
        $emailzoologic = 'contactozoologic@gmail.com';

        $validar = Validator::make($dato, [
            "nombre" => 'required|max:100',
            "telefono" => 'required|max:10',
            "email" => 'required|max:70',
            "mensaje" => 'required|max:400'
        ]);

        if ($validar->fails()) {
            throw ValidationException::withMessages($validar->errors()->toArray());
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
