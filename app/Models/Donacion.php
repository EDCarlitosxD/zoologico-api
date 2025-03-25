<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Donacion",
 *     title="Donacion",
 *     description="Modelo de Donacion",
 *     required={"id_usuario", "monto", "email", "fecha"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="id_usuario", type="integer", example=123),
 *     @OA\Property(property="monto", type="number", format="float", example=50.00),
 *     @OA\Property(property="email", type="string", example="usuario@example.com"),
 *     @OA\Property(property="fecha", type="string", format="date-time", example="2025-03-09T12:00:00Z")
 * )
 */
class Donacion extends Model
{

    use HasFactory;


    protected $table = "donaciones";

    protected $fillable = [
        'id_usuario',
        'monto',
        'email',
        'fecha'
    ];
}
