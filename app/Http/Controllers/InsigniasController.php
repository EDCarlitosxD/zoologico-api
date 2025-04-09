<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insignias;
use App\Models\VistaInsigniasUser;
use App\Services\InsigniaService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @OA\Tag(
 *     name="Insignias",
 *     description="APIs para gestionar insignias"
 * )
 */
class InsigniasController
{
    protected $insigniaService;

    public function __construct(InsigniaService $insigniaService)
    {
        $this->insigniaService = $insigniaService;
    }
    /**
     * @OA\Get(
     *     path="/insignias",
     *     tags={"Insignias"},
     *     summary="Lista todas las insignias",
     *     description="Obtiene todas las insignias disponibles. Puede filtrar por estado (1=Activo, 0=Inactivo).",
     *     @OA\Parameter(
     *         name="estado",
     *         in="query",
     *         description="Filtrar insignias por estado (1=Activo, 0=Inactivo)",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de insignias obtenida con éxito",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Insignia")
     *         )
     *     )
     * )
     */
    public function getAll(Request $request)
    {
        $query = Insignias::query();
        if ($request->has('estado')) {
            $query->where('estado', $request->input('estado'));
        }
        return response($query->get(), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/insignias/{id}",
     *     tags={"Insignias"},
     *     summary="Obtiene una insignia por su ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la insignia",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Insignia obtenida con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/Insignia")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Insignia no encontrada"
     *     )
     * )
     */
    public function getById($id)
    {
        $buscar = Insignias::findOrFail($id);


        if (!$buscar) {
            throw new NotFoundHttpException('No existe');
        } else {
            $buscar->imagen = asset('storage') . '/' . ($buscar->imagen);
            return response()->json($buscar);
        }
    }


    /**
     * @OA\Get(
     *     path="/insignias/user/{id}",
     *     tags={"Insignias"},
     *     summary="Obtiene la insignia de un usuario",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Insignia obtenida con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/Insignia")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Insignia no encontrada"
     *     )
     * )
     */
    public function getByUser($id)
    {
        $view = VistaInsigniasUser::where('id_usuario', $id)->firstOrFail();
        $view->imagen = asset('storage') . '/' . ($view->imagen);
        return $view;
    }

    /**
     * @OA\Post(
     *     path="/insignias/guardar",
     *     tags={"Insignias"},
     *     summary="Crea una nueva insignia",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos de la insignia",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"imagen", "nombre", "cantidad"},
     *                 @OA\Property(
     *                     property="imagen",
     *                     type="file",
     *                     format="binary",
     *                     description="Imagen de la insignia"
     *                 ),
     *                 @OA\Property(
     *                     property="nombre",
     *                     type="string",
     *                     description="Nombre de la insignia"
     *                 ),
     *                 @OA\Property(
     *                     property="cantidad",
     *                     type="integer",
     *                     description="Cantidad de insignias"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Insignia creada con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/Insignia")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la validación o en la creación"
     *     ),
     * )
     */
    public function guardar(Request $request)
    {

        try {
            $insignia = $this->insigniaService->crearInsignia($request);
            return response()->json(['message' => 'Insignia guardada con éxito', 'Insignia' => $insignia], 201);
        } catch (\Exception $error) {
            return response()->json(['error' => 'Error al guardar la insignia: ' . $error->getMessage()], 400);
        }
    }

    /**
     * Actualizar una insignia por ID
     * 
     * @OA\Put(
     *     path="/insignias/actualizar/{id}",
     *     tags={"Insignias"},
     *     summary="Actualiza los datos de una insignia existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la insignia",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos de la insignia",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"imagen", "nombre", "cantidad"},
     *                 @OA\Property(
     *                     property="imagen",
     *                     type="file",
     *                     format="binary",
     * example="imagen.jpg",
     *                     description="Imagen de la insignia"
     *                 ),
     *                 @OA\Property(
     *                     property="nombre",
     *                     type="string",
     *                     example="Insignia 1",
     *                     description="Nombre de la insignia"
     *                 ),
     *                 @OA\Property(
     *                     property="cantidad",
     *                     type="integer",
     *                     example="10",
     *                     description="Cantidad de insignias"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Insignia actualizada con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/Insignia")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la validación o en la actualización"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Insignia no encontrada"
     *     )
     * )
     */
    public function actualizar(Request $request, $id)
    {

        try {
            $insignia = $this->insigniaService->actualizarInsignia($request, $id);
            return response()->json(['message' => 'Insignia guardada con éxito', 'Insignia' => $insignia], 201);
        } catch (\Exception $error) {
            return response()->json(['error' => 'Error al guardar la insignia: ' . $error->getMessage()], 400);
        }
    }

    /**
     * Cambiar el estado de una insignia (activar/desactivar)
     * 
     * @OA\Put(
     *     path="/insignias/eliminar/{id}",
     *     tags={"Insignias"},
     *     summary="Actualiza el estado de una insignia",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la insignia",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"estado"},
     *             @OA\Property(property="estado", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estado de la insignia actualizado con éxito"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Insignia no encontrada"
     *     )
     * )
     */
    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|boolean',
        ]);

        $insignia = Insignias::findOrFail($id);
        $insignia->estado = $request->input('estado');
        $insignia->save();

        return response()->json(['message' => 'Insignia estado actualizado con éxito']);
    }
}
