<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\VentaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MembresiasUsuariosController
{
    protected $ventaService;

    public function __construct(VentaService $ventaService)
    {
        $this->ventaService = $ventaService;
    }

/**
 * @OA\Post(
 *     path="/venta/membresia",
 *     summary="Procesar una venta de membresía",
 *     tags={"Ventas"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Datos de la venta",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="id_membresia",
 *                     type="integer",
 *                     description="ID de la membresía a comprar",
 *                     example=1
 *                 ),
 *                 @OA\Property(
 *                     property="id_usuario",
 *                     type="integer",
 *                     description="ID del usuario que compra la membresía",
 *                     example=5
 *                 ),
 *                 @OA\Property(
 *                     property="fecha_compra",
 *                     type="string",
 *                     format="date",
 *                     description="Fecha de compra",
 *                     example="2025-03-12"
 *                 ),
 *                 @OA\Property(
 *                     property="meses",
 *                     type="integer",
 *                     description="Cantidad de meses que durará la membresía",
 *                     example=3
 *                 ),
 *                 @OA\Property(
 *                     property="fecha_vencimiento",
 *                     type="string",
 *                     format="date",
 *                     description="Fecha de vencimiento",
 *                     example="2025-06-12"
 *                 ),
 *                 @OA\Property(
 *                     property="precio_total",
 *                     type="float",
 *                     description="Precio total",
 *                     example=1000.50
 *                 ),
 *                 @OA\Property(
 *                     property="token",
 *                     type="string",
 *                     description="Token del usuario",
 *                     example="xyz789abc"
 *                 ),
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                     format="email",
 *                     description="Correo electrónico del usuario",
 *                     example="usuario@example.com"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Venta procesada correctamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Membresía vendida con éxito"),
 *             @OA\Property(property="Membresia", type="object")    
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error al procesar la venta",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Error al procesar la venta")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Usuario no autenticado",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Unauthenticated")
 *         )
 *     )
 * )
 */

    public function guardar(Request $request)
    {
        $token = Str::uuid();
        $email = Auth::user()->email;
        $nombre = Auth::user()->nombre_usuario;
        $fechaactual = date("Y-m-d");


        DB::beginTransaction();
        try {

            $membresia = $this->ventaService->procesarVentaMembresia($request, $token, $fechaactual, $email);
            DB::commit();
            return response()->json(['message' => 'Membresia vendida con éxito', 'Membresia' => $membresia], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtener la membresía activa de un usuario
     * 
     * @OA\Get(
     *     path="/user/membership/{userId}",
     *     summary="Obtener la membresía activa de un usuario",
     *     tags={"Membresias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Membresía obtenida con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/Membresia")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Usuario no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuario no autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function getUserMembership($userId)
    {
        // Verificar si el usuario existe
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        // Consultar la membresía activa del usuario
        $membership = DB::table('membresias_usuarios as mu')
            ->join('membresias as m', 'mu.id_membresia', '=', 'm.id')
            ->select(
                'mu.id', 
                'mu.id_membresia',
                'mu.fecha_compra',
                'mu.fecha_vencimiento',
                'm.nombre as tipo_membresia',
                'm.descuento_tours',
                'm.descuento_alimentos_souvenirs',
                'm.entradas_ilimitadas',
                'm.acceso_eventos'
                // Puedes agregar más campos según necesites
            )
            ->where('mu.id_usuario', $userId)
            ->where('mu.fecha_vencimiento', '>=', date('Y-m-d'))
            ->where('mu.estado', 1)
            ->orderBy('mu.fecha_vencimiento', 'desc')
            ->first();

        if ($membership) {
            return response()->json([
                'hasMembership' => true,
                'membershipDetails' => $membership
            ], Response::HTTP_OK);
        }

        return response()->json([
            'hasMembership' => false
        ], Response::HTTP_OK);
    }
}
