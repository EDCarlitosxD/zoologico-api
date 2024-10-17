<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private $emailService;
    //
    public function __construct(EmailService $emailService) {
        $this->emailService = $emailService;
    }

    public function login(Request $request){

        $request->validate(['email' => 'email','password' => 'required|max:255']);

        $emailRe = $request->email;
        $passwordRe = $request->password;

        $user = User::where('email',$emailRe)->first();

        if(! $user || ! Hash::check($passwordRe, $user->password)){
            throw ValidationException::withMessages([
                "message" => "Las credenciales son incorrectas"
            ]);
        }
        if($user->email_verified_at == null){
            return response(["message"=> 'Email no verificado'], Response::HTTP_FORBIDDEN);
        }

     return $user->createToken($request->device_name)->plainTextToken;
    }
    public function register(Request $request){}
    public function logout(Request $requst){
        return "hola mundo";
    }
}
