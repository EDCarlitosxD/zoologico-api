<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Reservas",
 *     description="APIs para la gestión de reservas"
 * )
 */
class ReservaController
{
    /**
     * @OA\Get(
     *     path="/reservas",
     *     summary="Obtener todas las reservas",
     *     tags={"Reservas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de reservas obtenida correctamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="precio_total", type="number", format="float", example=99.99),
     *                 @OA\Property(property="fecha", type="string", format="date", example="2025-03-10"),
     *                 @OA\Property(property="cantidad", type="integer", example=2),
     *                 @OA\Property(property="horario_inicio", type="string", format="time", example="10:00:00"),
     *                 @OA\Property(property="horario_fin", type="string", format="time", example="12:00:00"),
     *                 @OA\Property(property="titulo", type="string", example="Safari Nocturno"),
     *                 @OA\Property(property="nombre_completo", type="string", example="Juan Pérez")
     *             )
     *         )
     *     )
     * )
     */
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
