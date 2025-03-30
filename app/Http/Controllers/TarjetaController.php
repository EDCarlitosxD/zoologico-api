<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tarjeta;
use App\Services\TarjetaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarjetaController
{
    protected $tarjetaService;

    public function __construct(TarjetaService $tarjetaService)
    {
        $this->tarjetaService = $tarjetaService;
    }


    /**
     * @OA\Post(
     *     path="/tarjeta", 
     *     tags={"Tarjetas"},
     *     summary="Guardar tarjeta",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos de la tarjeta",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"numero_tarjeta", "fecha_vencimiento", "cvv"},
     *                 @OA\Property(
     *                     property="numero_tarjeta",
     *                     type="string",   
     *                     example="1234 5678 9012 3456"
     *                 ),
     *                 @OA\Property(
     *                     property="fecha_vencimiento",
     *                     type="string",
     *                     format="date",
     *                     example="2023-12-31"
     *                 ),
     *                 @OA\Property(
     *                     property="cvv",
     *                     type="integer",
     *                     example=123
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarjeta guardada conxito",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="id",
     *                 type="integer",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="numero_tarjeta",
     *                 type="string",
     *                 example="1234 5678 9012 3456"
     *             ),
     *             @OA\Property(
     *                 property="fecha_vencimiento",
     *                 type="string",
     *                 format="date",     
     *                 example="2023-12-31"
     *             ),
     *             @OA\Property(
     *                 property="cvv",
     *                 type="integer",
     *                 example=123
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la validación",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",   
     *                 example="Error en la validación"
     *             )
     *         )
     *     )
     * )
     */
    public function guardar (Request $request){

        $idusuario = Auth::user()->id;

        $tarjeta = $this->tarjetaService->procesarTarjeta($request, $idusuario);

        return $tarjeta;

    }

    /**
     * @OA\Delete(
     *     path="/tarjeta/{id}",
     *     tags={"Tarjetas"},
     *     summary="Eliminar tarjeta",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la tarjeta",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarjeta eliminada conxito",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Tarjeta eliminada con exito"
     *             )
     *         )
     *     )
     * )
     */
    public function eliminar($id){


        $eliminartarjeta = $this->tarjetaService->eliminarTarjeta($id);

        return $eliminartarjeta;

    }

    /**
     * @OA\Get(
     *     path="/tarjetas/{id}",
     *     tags={"Tarjetas"},
     *     summary="Obtener tarjetas",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarjetas obtenidas conxito",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Tarjeta"))
     *     )
     * )
     */
    public function getTarjetas($id){
        return Tarjeta::where('id_usuario',$id)->get();
    }

}
