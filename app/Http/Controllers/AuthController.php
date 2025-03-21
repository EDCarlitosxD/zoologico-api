<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CuentaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController
{
    protected $cuentaService;

    public function __construct(CuentaService $cuentaService) {
        $this->cuentaService = $cuentaService;

    }

    public function login(Request $request){

        $request->validate(['email' => 'email|required',
        'password' => 'required|max:255',
    ]);

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
        $token = $user->createToken($user->email)->plainTextToken;
        return response(["token" => $token, "user" =>  $user],Response::HTTP_OK);
    }

    public function register(Request $request){
        $request->validate([
            "nombre_usuario" => "required|max:255",
            "apellido" => "required",
            "nombre" => "required|max:255",
            "email" => "required",
            'password' => 'required|min:8',
        ]);

        $datos = $request->all();
        // $datos["id_rol"] = 1; // Set default role ID
        $datos["rol"] = "cliente";
        $datos["password"] = Hash::make($request->password);
        $datos["estado"] = 1;
        $user = User::create($datos);

        $user->sendEmailVerificationNotification();

        return response([], Response::HTTP_OK);
    }
    public function logout(Request $request){
        $request->tokens()->delete();
        return response(["message" => "tokens removed"], Response::HTTP_OK);
    }

    public function EditarDatos(Request $request){
        $idusuario = Auth::user()->id;

        $editardatos = $this->cuentaService->update($request, $idusuario);

        return $editardatos;
    }
}
