<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Guia",
 *     title="Guia",
 *     description="Modelo de Guia",
 *     required={"nombre_completo", "disponible", "estado"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre_completo", type="string", example="Juan Pérez"),
 *     @OA\Property(property="disponible", type="boolean", example=true),
 *     @OA\Property(property="estado", type="boolean", example=true)
 * )
 */
class Guia extends Model
{

    use HasFactory;

    protected $fillable = [
        'nombre_completo',
        'disponible',
        'estado',
    ];
}
