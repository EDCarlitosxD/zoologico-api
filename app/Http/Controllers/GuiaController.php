<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Guia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
/**
 * @OA\Tag(
 *     name="Guías",
 *     description="APIs para la gestión de guías"
 * )
 */
class GuiaController
{
    //
    /**
     * @OA\Get(
     *     path="/guias",
     *     summary="Obtener todas las guías",
     *     tags={"Guias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de guías obtenida correctamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre_completo", type="string", example="Juan Pérez"),
     *                 @OA\Property(property="estado", type="boolean", example=true),
     *                 @OA\Property(property="disponible", type="boolean", example=true)
     *             )
     *         )
     *     )
     * )
     */
    public function getAll(Request $request){
        $query = Guia::query();
        $activo = $request->input('estado','');

        if($request->has('estado')){
            $query->where('estado',$activo);
        }
        return response($query->get(),Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/guias/{id}",
     *     summary="Obtener una guía por su ID",
     *     tags={"Guias"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la guía a obtener",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Guía obtenida correctamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nombre_completo", type="string", example="Juan Pérez"),
     *             @OA\Property(property="estado", type="boolean", example=true),
     *             @OA\Property(property="disponible", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Guía no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Guía no encontrada")
     *         )
     *     )
     * )
     */
    public function getById($id){
        return Guia::findOrFail($id);
    }


    /**
     * @OA\Post(
     *     path="/guias",
     *     summary="Registrar una nueva guía",
     *     tags={"Guias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     * description="Datos de la guía",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"nombre_completo"},
     *                 @OA\Property(property="nombre_completo", type="string", example="Juan Pérez")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Guía registrada correctamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="guia", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre_completo", type="string", example="Juan Pérez"),
     *                 @OA\Property(property="estado", type="boolean", example=true),
     *                 @OA\Property(property="disponible", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al registrar la guía",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Error al registrar la guía")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No autorizado")
     *         )
     *     )
     * )
     */
    public function save(Request $request){
        $request->validate([
            'nombre_completo' => 'required|max:200',
        ]);
        $datos = $request->all();

        $boleto = new Guia($datos);
        $boleto->save();
        return response(["guia" => $boleto],Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *     path="/guias/{id}",
     *     summary="Actualizar una guía",
     *     tags={"Guias"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la guía a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos de la guía a actualizar",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"nombre_completo"},
     *                 @OA\Property(property="nombre_completo", type="string", example="Juan Pérez"),
     *                 @OA\Property(property="disponible", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Guía actualizada correctamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="guia", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre_completo", type="string", example="Juan Pérez"),
     *                 @OA\Property(property="estado", type="boolean", example=true),
     *                 @OA\Property(property="disponible", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al actualizar la guía",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Error al actualizar la guía")
     *         )
     *     ),
     * )
     */
    public function actualizar(Request $request,$id){
        $request->validate([
            'nombre_completo' => 'max:200',
            //"estado" => "boolean",
            // "disponible" => "boolean"
        ]);

        $guia = Guia::find($id);

        // Verificar si el registro existe
        if (!$guia) {
            return response()->json(['mensaje' => 'Guía no encontrada'], Response::HTTP_NOT_FOUND);
        }

        // Actualizar los campos del registro
        $guia->fill($request->all());

        // Guardar los cambios en la base de datos
        $guia->save();

        return response(["guia" => $guia],Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *     path="/guias/eliminar/{id}",
     *     summary="Actualizar el estado de una guía",
     *     tags={"Guias"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la guía a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="estado", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estado de la guía actualizado correctamente"
     *     )
     * )
     */
    public function actualizarEstado(Request $request, $id){
        $request->validate([
            'estado'=>'required|boolean',
        ]);

        $guia = Guia::findOrFail($id);

        $guia->estado = $request->input('estado');
        $guia->save();

        return response()->json(['message' => 'Guia estado actualizado con exito']);

    }

}
