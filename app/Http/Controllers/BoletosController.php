<?php

namespace App\Http\Controllers;

use App\Models\boletos;
use App\Http\Controllers\Controller;
use App\Models\venta_boletos;
use App\Services\BoletoService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;

/**
 * @OA\Tag(
 *     name="Boletos",
 *     description="APIs para la gestión de boletos"
 * )
 */
class BoletosController
{
    protected $boletoService;
    public function __construct(BoletoService $boletoService)
    {
        $this->boletoService = $boletoService;
    }

    /**
     * @OA\Get(
     *     path="/boletos/usuario",
     *     summary="Obtener boletos comprados por un usuario",
     *     tags={"Boletos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista de boletos comprados obtenida correctamente")
     *  )
     */
     public function boletosUsuario()
     {
         $id_usuario = Auth::user()->id;

         $resultado = $this->boletoService->TraerComprasUsuario($id_usuario);

         return response()->json($resultado);
     }

    /**
     * @OA\Get(
     *     path="/admin/boletos",
     *     summary="Obtener boletos existentes",
     *     tags={"Boletos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista de boletos obtenida correctamente")
     * )
     */
    public function boletosExistentes()
    {
        $boletosExistentes = $this->boletoService->traerBoletosExistentes();

        return response()->json($boletosExistentes);
    }


    /**
     * @OA\Get(
     *     path="/boletos",
     *     summary="Obtener todos los boletos",
     *     tags={"Boletos"},
     *     @OA\Response(response=200, description="Lista de boletos obtenida correctamente")
     * )
     */
    public function all(Request $request)
    {
        $query = boletos::query()->where('estado', 1);



        return $query->get()->map(function ($boleto) {
            $boleto->imagen = asset('storage/' . $boleto->imagen);
            return $boleto;
        });
    }


    /**
     * @OA\Get(
     *     path="/boletos/{id}",
     *     summary="Obtener un boleto por su ID",
     *      tags={"Boletos"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Boleto obtenido correctamente") 
     *  )
     */
    public function getById($id)
    {
        $boleto = boletos::findOrFail($id);
        $boleto->imagen = asset('storage') . '/' . ($boleto->imagen);

        return $boleto;
    }

    /**
     * @OA\Post(
     *     path="/boletos",
     *     summary="Crear un nuevo boleto",
     *     tags={"Boletos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del boleto a crear",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"titulo", "descripcion", "precio", "imagen", "descripcion_card", "advertencias"},
     *                 @OA\Property(property="titulo", type="string", example="Boleto VIP"),
     *                 @OA\Property(property="descripcion", type="string", example="Acceso exclusivo"),
     *                 @OA\Property(property="precio", type="number", format="float", example=99.99),
     *                 @OA\Property(property="imagen", type="string", format="binary"),
     *                 @OA\Property(property="descripcion_card", type="string", example="Acceso VIP"),
     *                 @OA\Property(property="advertencias", type="string", example="No incluye alimentos"),
     *             )
     *         ),
     *     ),
     *     @OA\Response(response=201, description="Boleto creado con éxito"),
     *     @OA\Response(response=400, description="Error al crear el boleto"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function save(Request $request)
    {

        try {
            $insignia = $this->boletoService->createBoleto($request);
            return response()->json(['message' => 'Boleto guardada con éxito', 'Boleto' => $insignia], 201);
        } catch (\Exception $error) {
            return response()->json(['error' => 'Error al guardar el boleto: ' . $error->getMessage()], 400);
        }
    }

    

     /**
      * @OA\Put(
      *     path="/boletos/eliminar/{id}",
      *     summary="Eliminar un boleto (eliminación lógica)",
      *     tags={"Boletos"},
      *     security={{"bearerAuth":{}}},
      *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
      *     @OA\RequestBody(
      *         required=true,
      *         @OA\JsonContent(
      *             required={"estado"},
      *             @OA\Property(property="estado", type="boolean", example=false)
      *         )
      *     ),
      *     @OA\Response(response=200, description="Boleto eliminado correctamente")
      * )
      */
     public function delete(Request $request, $id)
     {
         $request->validate([
             'estado' => 'required|boolean',
         ]);

         $boleto = boletos::findOrFail($id);

         $boleto->estado = $request->input('estado');
         $boleto->save();

         return response(['message' => 'Boleto estado actualizado con exito'], Response::HTTP_ACCEPTED);
     }


    /**
     * @OA\Get(
     *     path="/venta/boletos",
     *     summary="Obtener boletos vendidos",
     *     tags={"Boletos"},
     *     @OA\Response(response=200, description="Lista de boletos vendidos obtenida correctamente")
     * )
     */
    public function boletosVendidos()
    {
        $ventas = DB::table('venta_boletos as venta')
            ->join('boletos', 'venta.id_boleto', '=', 'boletos.id')
            ->select(
                'venta.id as id',
                'boletos.titulo as titulo',
                'venta.precio_total as precio_total',
                'venta.cantidad as cantidad'
            )
            ->paginate(20);

        return response()->json($ventas);
    }

    /**
     * @OA\Put(
     *     path="/boletos/actualizar/{id}",
     *     summary="Actualizar un boleto",
     *     tags={"Boletos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del boleto a actualizar",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"titulo", "descripcion", "precio", "imagen", "descripcion_card", "advertencias"},  
     *                 @OA\Property(property="titulo", type="string", example="Boleto VIP"),
     *                 @OA\Property(property="descripcion_card", type="string", example="Acceso VIP"),
     *                 @OA\Property(property="descripcion", type="string", example="Acceso exclusivo"),
     *                 @OA\Property(property="advertencias", type="string", example="No incluye alimentos"),
     *                 @OA\Property(property="precio", type="number", format="float", example=99.99),
     *                 @OA\Property(property="imagen", type="string", format="binary"),
     *             )
     *         ),
     *     ),
     *     @OA\Response(response=200, description="Boleto actualizado correctamente"),
     *     @OA\Response(response=400, description="Error al actualizar el boleto"),
     *     @OA\Response(response=401, description="No autorizado")     
     * )
     */
    public function actualizar(Request $request, $id)
    {
        try{
            $update = $this->boletoService->updateBoleto($request, $id);
            DB::commit();
            return response()->json(["message" => "Boleto actualizado correctamente", "boleto" => $update], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(["error" => $e->getMessage()], 500);
        }
        
    }
}
