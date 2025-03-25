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

/**
 * @OA\Tag(
 *     name="Autenticación",
 *     description="APIs para gestionar la autenticación de usuarios"
 * )
 */
class AuthController
{
    protected $cuentaService;

    public function __construct(CuentaService $cuentaService)
    {
        $this->cuentaService = $cuentaService;
    }

    /**
     * Iniciar sesión
     * 
     * @OA\Post(
     *     path="/login",
     *     tags={"Autenticación"},
     *     summary="Autenticación de usuario",
     *     description="Inicia sesión con un email y contraseña, y devuelve un token de acceso.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="usuario@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="abcdef123456"),
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales incorrectas"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required|max:255',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            throw new NotFoundHttpException("No existe");
        }

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                "message" => "Las credenciales son incorrectas"
            ]);
        }

        $token = $user->createToken($user->email)->plainTextToken;
        return response(["token" => $token, "user" =>  $user], Response::HTTP_OK);
    }

    /**
     * Registrar un nuevo usuario
     * 
     * @OA\Post(
     *     path="/register",
     *     tags={"Autenticación"},
     *     summary="Registra un nuevo usuario",
     *     description="Registra un nuevo usuario en la plataforma y envía un correo de verificación.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre_usuario", "apellido", "nombre", "email", "password"},
     *             @OA\Property(property="nombre_usuario", type="string", example="johndoe"),
     *             @OA\Property(property="apellido", type="string", example="Doe"),
     *             @OA\Property(property="nombre", type="string", example="John"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro exitoso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la validación"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            "nombre_usuario" => "required|max:255",
            "apellido" => "required",
            "nombre" => "required|max:255",
            "email" => "required|email",
            "password" => "required|min:8",
        ]);

        $datos = $request->all();
        $datos["rol"] = "cliente";
        $datos["password"] = Hash::make($request->password);
        $datos["estado"] = 1;
        $user = User::create($datos);

        $user->sendEmailVerificationNotification();

        return response([], Response::HTTP_OK);
    }























    /**
     * Cerrar sesión
     * 
     * @OA\Post(
     *     path="/logout",
     *     tags={"Autenticación"},
     *     summary="Cerrar sesión",
     *     description="Elimina todos los tokens de acceso del usuario autenticado.",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Tokens eliminados correctamente"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response(["message" => "Tokens removed"], Response::HTTP_OK);
    }

    /**
     * Editar datos del usuario autenticado
     * 
     * @OA\Put(
     *     path="/cuenta",
     *     tags={"Autenticación"},
     *     summary="Editar datos del usuario",
     *     description="Permite a un usuario autenticado actualizar su información personal.",
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Alberto"),
     *             @OA\Property(property="apellido", type="string", example="Gonzalez"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="foto_perfil", type="file", format="binary", example="imagen.jpg") 
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos actualizados con éxito"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     )
     * )
     */
    public function EditarDatos(Request $request)
    {
        $idusuario = Auth::user()->id;
        $editardatos = $this->cuentaService->update($request, $idusuario);
        return $editardatos;
    }
}