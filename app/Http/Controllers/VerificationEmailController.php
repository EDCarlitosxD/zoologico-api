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
