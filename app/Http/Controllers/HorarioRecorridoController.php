<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HorarioRecorrido;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class HorarioRecorridoController
{
    //

    public function addNuevoHorario(Request $request)
    {
        $request->validate([
            'horario_inicio' => 'time|required',
            'horario_fin' => 'time|required',
            'fecha' => 'date',
            'id_recorrido' => 'required|numeric',
            'id_guia' => 'required|numeric',
            'disponible' => 'boolean',
        ]);

        $datos = $request->all();
        $horarioRecorrido = new HorarioRecorrido($datos);
        $horarioRecorrido->save();
        return response(['data' => $horarioRecorrido], Response::HTTP_CREATED);
    }

    /**
 * @OA\Get(
 *     path="/api/recorridos/{id}/horarios",
 *     summary="Obtener horarios agrupados por fecha para un recorrido específico",
 *     description="Este endpoint devuelve los horarios disponibles para un recorrido específico, agrupados por fecha.",
 *     operationId="getHorariosGroupByRecorridos",
 *     tags={"Recorridos"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID del recorrido para el cual se desean obtener los horarios",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Horarios agrupados por fecha",
 *         @OA\JsonContent(
 *             type="object",
 *             example={
 *                 "2023-10-01": {
 *                     {
 *                         "id": 1,
 *                         "horario_inicio": "09:00:00",
 *                         "horario_fin": "10:00:00",
 *                         "disponible": 1,
 *                         "fecha": "2023-10-01"
 *                     },
 *                     {
 *                         "id": 2,
 *                         "horario_inicio": "11:00:00",
 *                         "horario_fin": "12:00:00",
 *                         "disponible": 1,
 *                         "fecha": "2023-10-01"
 *                     }
 *                 },
 *                 "2023-10-02": {
 *                     {
 *                         "id": 3,
 *                         "horario_inicio": "10:00:00",
 *                         "horario_fin": "11:00:00",
 *                         "disponible": 1,
 *                         "fecha": "2023-10-02"
 *                     }
 *                 }
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Recorrido no encontrado",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="error",
 *                 type="string",
 *                 example="Recorrido no encontrado"
 *             )
 *         )
 *     )
 * )
 */
    public function getHorariosGroupByRecorridos($id)
    {
        $recorridos = HorarioRecorrido::where('id_recorrido',$id)
        ->where('disponible', '=',1)
            ->get();

            $agrupadosPorDia = $recorridos->groupBy('fecha')->map(function ($horarios, $fecha) {
                return $horarios->map(function ($horario) use ($fecha) {
                    return [
                        'id' => $horario->id,
                        'horario_inicio' => $horario->horario_inicio,
                        'horario_fin' => $horario->horario_fin,
                        'disponible' => $horario->disponible,
                        'fecha' => $fecha // Incluimos la fecha aquí
                    ];
                });
            });

        return response()->json($agrupadosPorDia);
    }



    public function getTourAndScheduleById($id_horario)
    {
        // Obtener el horario por ID
        $horario = HorarioRecorrido::with('recorrido') // Relación con el modelo Recorrido
            ->where('id', $id_horario)
            ->first();

        if (!$horario) {
            return response()->json(['error' => 'Horario no encontrado'], 404);
        }

        // Formatear la respuesta en JSON

        return response()->json($horario, 200);
    }


    public function getById($id){
        return HorarioRecorrido::where('id_recorrido','=',$id)->get();
    }

}
