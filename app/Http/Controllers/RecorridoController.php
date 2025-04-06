<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HorarioRecorrido;
use App\Services\RecorridoService;
use Exception;
use App\Models\Recorrido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Recorridos",
 *     description="APIs para la gestión de recorridos turísticos"
 * )
 */
class RecorridoController
{
    protected $recorridoService;

    public function __construct(RecorridoService $recorridoService)
    {
        $this->recorridoService = $recorridoService;
    }

/**
 * @OA\Post(
 *     path="/recorridos/guardar",
 *     summary="Crear un recorrido",
 *     security={{"bearerAuth":{}}},
 *     tags={"Recorridos"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Datos del recorrido",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 type="object",
 *                 required={"titulo", "precio", "duracion", "descripcion"},
 *                 @OA\Property(
 *                     property="titulo",
 *                     type="string",
 *                     example="Safari Nocturno"
 *                 ),
 *                 @OA\Property(
 *                     property="precio",
 *                     type="number",
 *                     format="float",  
 *                     example=49.99
 *                 ),
 *                 @OA\Property(
 *                     property="duracion",
 *                     type="string",
 *                     format="time",
 *                     example="02:00:00"
 *                 ),
 *                 @OA\Property(
 *                     property="descripcion",
 *                     type="string",
 *                     example="Un recorrido emocionante por la selva."
 *                 ),
 *                 @OA\Property(
 *                     property="descripcion_incluye",
 *                     type="string",
 *                     example="Guía turístico, refrigerios."
 *                 ),
 *                 @OA\Property(
 *                     property="descripcion_importante_reservar",
 *                     type="string",    
 *                     example="Reservar con 48 horas de anticipación."
 *                 ),
 *                 @OA\Property(
 *                     property="img_recorrido",
 *                     type="file",
 *                     format="binary",
 *                     example="safari.jpg"
 *                 )                
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Recorrido creado exitosamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="recorrido y horarios agregados correctamente")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error en la validación",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="El campo 'img_recorrido' es requerido.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error en el servidor",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Error al guardar el recorrido.")
 *         )
 *     )
 * )
 */
    public function guardar(Request $request){
        DB::beginTransaction();
        try{
            $registro = $this->recorridoService->crearRecorrido($request);
            DB::commit();
            return $registro;
        } catch(Exception $e){
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    

/**
     * @OA\Put(
     *     path="/recorridos/actualizar/{id}",
     *     summary="Actualizar un recorrido",
     *     tags={"Recorridos"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Recorrido")
     *     ),
     *     @OA\Response(response=200, description="Recorrido actualizado con éxito"),
     *     @OA\Response(response=400, description="Error en la validación"),
     *     @OA\Response(response=500, description="Error en el servidor")
     * )
     */
    public function actualizar(Request $request, $id)
    {
        
         try {
             $update = $this->recorridoService->updateDatos($request, $id);
             DB::commit();
             return response()->json(["message" => "Recorrido actualizado correctamente", "recorrido" => $update], 200);
         } catch (Exception $e) {
             DB::rollBack();
             return response()->json(["error" => $e->getMessage()], 500);
         }
     }
        //return response()->json(['mensaje' => 'Recorrido actualizado correctamente']);
    /**
     * Eliminar un recorrido (eliminación lógica)
     * 
     * @OA\Put(
     *     path="/recorridos/eliminar/{id}",
     *     tags={"Recorridos"},
     *     summary="Elimina lógicamente un recorrido",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del recorrido a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recorrido eliminado correctamente"
     *     )
     * )
     */
    public function eliminar(Request $request, $id){
        $eliminado = $this->recorridoService->eliminadoLogico($request, $id);
        return $eliminado;
    }

    /**
     * Obtener todos los recorridos activos
     * 
     * @OA\Get(
     *     path="/recorridos",
     *     tags={"Recorridos"},
     *     summary="Lista todos los recorridos activos",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de recorridos activos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Recorrido"))
     *     )
     * )
     */
    public function getAllRecorridosActive(){
        $recorridos = Recorrido::where('estado',1)->get();
        foreach ($recorridos as $recorrido) {
            $recorrido->img_recorrido = asset('storage') . '/' . ($recorrido->img_recorrido);
        }
        return $recorridos;
    }

    /**
     * Obtener todos los recorridos
     * 
     * @OA\Get(
     *     path="/admin/recorridos",
     *     tags={"Recorridos"},
     *     summary="Lista todos los recorridos",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de recorridos obtenida con éxito",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Recorrido"))
     *     )
     * )
     */
    public function getAllRecorridos(){
        return response(Recorrido::all(), Response::HTTP_OK);
    }

    /**
     * Obtener un recorrido por ID
     * 
     * @OA\Get(
     *     path="/recorridos/{id}",
     *     tags={"Recorridos"},
     *     summary="Obtiene un recorrido por su ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del recorrido",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recorrido obtenido con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/Recorrido")
     *     )
     * )
     */
    public function getById($id){
        $reco = Recorrido::findOrFail($id);
        $reco->img_recorrido = asset('storage'). '/' . ($reco->img_recorrido);
        return $reco;
    }

    /**
     * Cambiar el estado de un recorrido (activar/desactivar)
     * 
     * @OA\Put(
     *     path="/horario/{id}",
     *     tags={"Recorridos"},
     *     summary="Cambia el estado de un horario de un recorrido",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del horario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"active"},
     *             @OA\Property(property="active", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estado actualizado correctamente"
     *     )
     * )
     */
    public function estado(Request $request, $id){
        $request->validate([
            "active" => 'required|bool'
        ]);

        $registro = HorarioRecorrido::findOrFail($id);
        $registro->disponible = $request->input('active');
        $registro->save();

        return response()->json(["message" => "actualizado correctamente"]);
    }

    /**
     * @OA\Get(
     *     path="/recorridosemana",
     *     tags={"Recorridos"},
     *     summary="Obtiene recorridos reservados por semana",
     *     @OA\Response(
     *         response=200,
     *         description="Recorridos reservados por semana obtenidos con éxito",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Recorrido"))
     *     )
     * )
     */
    public function recorridosReservadosSemana(){
        $recorridos = $this->recorridoService->rreservadosSemana();
        return response($recorridos, Response::HTTP_OK);

    }
    /**
     * Obtener recorridos reservados por mes
     * @OA\Get(
     *     path="/recorridosmes",
     *     tags={"Recorridos"},
     *     summary="Obtiene recorridos reservados por mes",
     *     @OA\Response(
     *         response=200,
     *         description="Recorridos reservados por mes obtenidos con éxito",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Recorrido"))
     *     )
     * )
     */
    public function recorridosReservadosMes(){
        $recorridos = $this->recorridoService->rreservadosMes();
        return response($recorridos, Response::HTTP_OK);
    }


    /**
     * @OA\Get(
     *     path="/recorridosyear",
     *     tags={"Recorridos"},
     *     summary="Obtiene recorridos reservados por year",
     *     @OA\Response(
     *         response=200,
     *         description="Recorridos reservados por year obtenidos con éxito",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Recorrido"))
     *     )
     * )
     */
    public function recorridosReservadosYear(){
        $recorridos = $this->recorridoService->rreservadosYear();
        return response($recorridos, Response::HTTP_OK);

    }
}
