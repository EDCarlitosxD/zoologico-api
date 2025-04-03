<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Insignia",
 *     title="Insignia",
 *     description="Modelo de Insignia",
 *     required={"nombre", "imagen", "cantidad"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Aventurero"),
 *     @OA\Property(property="imagen", type="string", example="https://example.com/insignia.jpg"),
 *     @OA\Property(property="cantidad", type="integer", example=10),
 *     @OA\Property(property="estado", type="boolean", example=true)
 * )
 */
class Insignias extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'imagen',
        'cantidad',
        'estado'
    ];
}
