<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservaController
{
    //


    public function getReservas()
    {
        $ventas = DB::table('reservas as r')
            ->join('horario_recorridos as ho', 'r.id_horario_recorrido', '=', 'ho.id')
            ->join('recorridos as re', 'ho.id_recorrido', '=', 're.id')
            ->join('guias as gu', 'ho.id_guia', '=', 'gu.id')
            ->select(
                'r.precio_total',
                'r.fecha',
                'r.cantidad',
                'ho.horario_inicio',
                'ho.horario_fin',
                're.titulo',
                'gu.nombre_completo'
            )
            ->paginate(20);

        return response()->json($ventas);
    }
}
