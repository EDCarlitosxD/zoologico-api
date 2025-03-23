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


    public function getById($id){
        return Guia::findOrFail($id);
    }


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
     *     security={{"bearerAuth":{}}},
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
     *             @OA\Property(property="nombre_completo", type="string", example="Juan Pérez"),
     *             @OA\Property(property="estado", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Guía actualizada correctamente"
     *     )
     * )
     */
    public function actualizar(Request $request,$id){
        $request->validate([
            'nombre_completo' => 'max:200',
            "estado" => "boolean",
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
     *     security={{"bearerAuth":{}}},
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
