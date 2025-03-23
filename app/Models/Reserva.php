<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Reserva",
 *     title="Reserva",
 *     description="Modelo de Reserva",
 *     required={"id_usuario", "cantidad", "id_horario_recorrido", "precio_total", "fecha", "token"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="id_usuario", type="integer", example=123),
 *     @OA\Property(property="cantidad", type="integer", example=2),
 *     @OA\Property(property="id_horario_recorrido", type="integer", example=5),
 *     @OA\Property(property="precio_total", type="number", format="float", example=29.99),
 *     @OA\Property(property="fecha", type="string", format="date", example="2025-03-10"),
 *     @OA\Property(property="token", type="string", example="abc123xyz")
 * )
 */
class Reserva extends Model
{

    use HasFactory;
    protected $fillable = [
        'id_usuario',
        'cantidad',
        'id_horario_recorrido',
        'precio_total',
        'fecha',
        'token'
    ];
}
