<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HorarioRecorrido;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;

class HorarioRecorridoController
{
    //

    public function addNuevoHorario(Request $request){
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
        return response(['data' => $horarioRecorrido],Response::HTTP_CREATED);
    }


    public function getByIdRecorrido($id){
        return HorarioRecorrido::where('id_recorrido',$id)->get();
    }




}
