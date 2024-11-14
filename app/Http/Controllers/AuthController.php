<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController extends AnimalController
{
    //
    public function __construct() {
    }

    public function login(Request $request){

        $request->validate(['email' => 'email|required','password' => 'required|max:255']);

        $emailRe = $request->email;
        $passwordRe = $request->password;

        $user = User::where('email',$emailRe)->first();
        if(!$user){
            throw new NotFoundHttpException("No existe");
        }

        if(! $user || ! Hash::check($passwordRe, $user->password)){
            throw ValidationException::withMessages([
                "message" => "Las credenciales son incorrectas"
            ]);
        }
        $token = $user->createToken($request->device_name)->plainTextToken;
        return response(["token" => $token, "user" =>  $user],Response::HTTP_OK);
    }

    public function register(Request $request){
        $request->validate([
            "email" => "required",
            "password" => "max:255|required",
            "name" => "required|max:255"
        ]);

        $datos = $request->all();
        // $datos["id_rol"] = 1; // Set default role ID
        $datos["role"] = "cliente";
        $datos["password"] = Hash::make($request->password);
        $user = User::create($datos);

        $user->sendEmailVerificationNotification();

        return $request;
    }
    public function logout(Request $request){
        $request->tokens()->delete();
        return response(["message" => "tokens removed"], Response::HTTP_OK);
    }
}
