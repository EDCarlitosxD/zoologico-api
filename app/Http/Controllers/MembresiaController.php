<?php
namespace App\Http\Controllers;

use App\Models\Membresia;
use App\Services\MembresiaService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @OA\Tag(
 *     name="Membresias",
 *     description="APIs para gestionar membresías"
 * )
 */
class MembresiaController
{
    protected $membresiaService;
    public function __construct(MembresiaService $membresiaService)
    {
        $this->membresiaService = $membresiaService;
    }
    /**
     * Obtener todas las membresías (opcionalmente filtradas por estado)
     * 
     * @OA\Get(
     *     path="/membresias",
     *     tags={"Membresias"},
     *     summary="Lista todas las membresías",
     *     description="Obtiene todas las membresías disponibles. Puede filtrar por estado (1=Activo, 0=Inactivo).",
     *     @OA\Parameter(
     *         name="estado",
     *         in="query",
     *         description="Filtrar membresías por estado (1=Activo, 0=Inactivo)",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de membresías obtenida con éxito",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Membresia")
     *         )
     *     )
     * )
     */
    public function getAll(Request $request)
    {
        $query = Membresia::query();
        
        if ($request->has('estado')) {
            $query->where('estado', $request->input('estado'));
        }
        
        $membresias = $query->get();
        
        foreach ($membresias as $membresia) {
            $membresia->imagen = asset('storage') . '/' . $membresia->imagen;
        }
        
        return response($membresias, Response::HTTP_OK);
    }

    /**
     * Obtener una membresía por ID
     * 
     * @OA\Get(
     *     path="/membresias/{id}",
     *     tags={"Membresias"},
     *     summary="Obtiene una membresía por su ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la membresía",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Membresía obtenida con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/Membresia")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Membresía no encontrada"
     *     )
     * )
     */
    public function getById($id){
        $buscar = Membresia::findOrFail($id);
        if (!$buscar) {
            throw new NotFoundHttpException('No existe');
        } else {
            $buscar->imagen = asset('storage') . '/' . ($buscar->imagen);
            return response($buscar, Response::HTTP_OK);
        }
    }

    /**
     * Crear una nueva membresía
     * 
     * @OA\Post(
     *     path="/membresias",
     *     tags={"Membresias"},
     *     summary="Crea una nueva membresía",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "precio", "descripcion", "imagen"},
     *             @OA\Property(property="nombre", type="string", example="VIP"),
     *             @OA\Property(property="precio", type="number", example=99.99),
     *             @OA\Property(property="descripcion", type="string", example="Acceso ilimitado por un año"),
     *             @OA\Property(property="imagen", type="string", example="https://example.com/membresia.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Membresía creada con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/Membresia")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la validación o en la creación"
     *     )
     * )
     */
    public function guardar(Request $request){
        try {
            $membresia = $this->membresiaService->crearMembresia($request);
            return response()->json(['message' => 'Membresia guardada con éxito', 'Membresia' => $membresia], 201);
        } catch (\Exception $error) {
            return response()->json(['error' => 'Error al guardar la Membresia: ' . $error->getMessage()], 400);
        }
    }

    /**
     * Actualizar una membresía por ID
     * 
     * @OA\Put(
     *     path="/membresias/actualizar/{id}",
     *     tags={"Membresias"},
     *     summary="Actualiza los datos de una membresía existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la membresía",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "precio", "descripcion", "imagen"},
     *             @OA\Property(property="nombre", type="string", example="Premium"),
     *             @OA\Property(property="precio", type="number", example=149.99),
     *             @OA\Property(property="descripcion", type="string", example="Acceso premium por un año"),
     *             @OA\Property(property="imagen", type="string", example="https://example.com/membresia_premium.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Membresía actualizada con éxito"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Membresía no encontrada"
     *     )
     * )
     */
    public function actualizar(Request $request, $id){
        try {
            $membresia = $this->membresiaService->actualizarMembresia($request, $id);
            return response()->json(['message' => 'Membresia guardada con éxito', 'membresia' => $membresia], 201);
        } catch (\Exception $error) {
            return response()->json(['error' => 'Error al guardar la membresia: ' . $error->getMessage()], 400);
        }
    }

    /**
     * Cambiar el estado de una membresía (activar/desactivar)
     * 
     * @OA\Put(
     *     path="/membresias/eliminar/{id}",
     *     tags={"Membresias"},
     *     summary="Actualiza el estado de una membresía",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la membresía",
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
     *         description="Estado de la membresía actualizado con éxito"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Membresía no encontrada"
     *     )
     * )
     */
    public function actualizarEstado(Request $request, $id){
        $request->validate([
            'estado'=>'required|boolean',
        ]);

        $membresia = Membresia::findOrFail($id);
        $membresia->estado = $request->input('estado');
        $membresia->save();

        return response()->json(['message' => 'Estado de la membresía actualizado con éxito']);
    }
}
