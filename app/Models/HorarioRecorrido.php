<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="HorarioRecorrido",
 *     title="HorarioRecorrido",
 *     description="Modelo de Horario de Recorrido",
 *     required={"horario_inicio", "disponible", "id_recorrido", "id_guia", "fecha", "horario_fin"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="horario_inicio", type="string", format="time", example="09:00:00"),
 *     @OA\Property(property="disponible", type="boolean", example=true),
 *     @OA\Property(property="id_recorrido", type="integer", example=5),
 *     @OA\Property(property="id_guia", type="integer", example=3),
 *     @OA\Property(property="fecha", type="string", format="date", example="2025-03-10"),
 *     @OA\Property(property="horario_fin", type="string", format="time", example="11:00:00")
 * )
 */
class HorarioRecorrido extends Model
{

    use HasFactory;

    protected $fillable = [
        'horario_inicio',
        'disponible',
        'id_recorrido',
        'id_guia',
        'fecha',
        'horario_fin',
        'disponible'
    ];


    public function recorrido()
    {
        return $this->belongsTo(Recorrido::class, 'id_recorrido', 'id');
    }
}
