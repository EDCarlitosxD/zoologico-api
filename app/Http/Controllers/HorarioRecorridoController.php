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


    public function getHorariosGroupByRecorridos($id)
    {
        $recorridos = HorarioRecorrido::where('id_recorrido',$id)
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


}
