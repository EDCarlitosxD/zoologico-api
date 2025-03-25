<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insignias;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Insignias",
 *     description="APIs para gestionar insignias"
 * )
 */
class InsigniasController
{
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
        return response(Insignias::findOrFail($id), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/insignias",
     *     tags={"Insignias"},
     *     summary="Crea una nueva insignia",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "imagen", "cantidad"},
     *             @OA\Property(property="nombre", type="string", example="Explorador"),
     *             @OA\Property(property="imagen", type="string", example="url_de_imagen"),
     *             @OA\Property(property="cantidad", type="integer", example=10)
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
     *     )
     * )
     */
    public function guardar(Request $request)
    {
        $validatedData = $request->validate([
            "nombre" => "required|max:255",
            "imagen" => "required",
            "cantidad" => "required|numeric"
        ]);

        try {
            $validatedData['estado'] = 1;
            $insignia = Insignias::create($validatedData);
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
     *         @OA\JsonContent(
     *             required={"nombre", "imagen", "cantidad"},
     *             @OA\Property(property="nombre", type="string", example="Aventurero"),
     *             @OA\Property(property="imagen", type="string", example="url_de_imagen"),
     *             @OA\Property(property="cantidad", type="integer", example=20)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Insignia actualizada con éxito"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Insignia no encontrada"
     *     )
     * )
     */
    public function actualizar(Request $request, $id) {
        $request->validate([
            "nombre" => "required|max:255",
            "imagen" => "required",
            "cantidad" => "required|numeric"
        ]);

        $insignia = Insignias::findOrFail($id);
        $insignia->fill($request->all());
        $insignia->update();
        return response(["insignia" => $insignia], Response::HTTP_CREATED);
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
    public function actualizarEstado(Request $request, $id) {
        $request->validate([
            'estado' => 'required|boolean',
        ]);

        $insignia = Insignias::findOrFail($id);
        $insignia->estado = $request->input('estado');
        $insignia->save();

        return response()->json(['message' => 'Insignia estado actualizado con éxito']);
    }
}
