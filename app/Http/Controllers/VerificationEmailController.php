<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationEmailController 
{
    //
/**
     * Verifica el correo electrónico de un usuario.
     * @OA\Get(
     *     path="/email/verify/{id}/{hash}",
     *     tags={"Autenticación"},
     *     summary="Verificación de correo electrónico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *         description="ID del usuario"
     *     ),
     *     @OA\Parameter(
     *         name="hash",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *         description="Hash de verificación"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Verificación exitosa",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Verificación fallida",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="No se encontró el usuario"
     *             )
     *         )
     *     )
     * )
     */

    public function verify($id, $hash)
    {
        // Encuentra al usuario por ID
        $user = User::findOrFail($id);

        if (! hash_equals(sha1($user->getEmailForVerification()), (string) $hash)) {
            return false;
        }


        // Verifica si se encontró al usuario




        // Verifica el correo electrónico si aún no está verificado
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            $user->save();
        }

        return view('verification.verificacion');
    }

}
