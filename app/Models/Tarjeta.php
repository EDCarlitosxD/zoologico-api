<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Tarjeta",
 *     title="Tarjeta",
 *     description="Modelo de Tarjeta de Crédito",
 *     required={"fecha_expiracion", "banco", "numero_tarjeta", "nombre_tarjeta", "ccv", "tipo_tarjeta", "id_usuario"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="fecha_expiracion", type="string", format="date", example="2026-05"),
 *     @OA\Property(property="banco", type="string", example="Banco Ejemplo"),
 *     @OA\Property(property="numero_tarjeta", type="string", example="1234 5678 9012 3456"),
 *     @OA\Property(property="nombre_tarjeta", type="string", example="Juan Pérez"),
 *     @OA\Property(property="ccv", type="integer", example=123),
 *     @OA\Property(property="tipo_tarjeta", type="string", example="Crédito"),
 *     @OA\Property(property="id_usuario", type="integer", example=123)
 * )
 */
class Tarjeta extends Model
{

    use HasFactory;

    protected $fillable = [
        'fecha_expiracion',
        'banco',
        'numero_tarjeta',
        'nombre_tarjeta',
        'ccv',
        'tipo_tarjeta',
        'id_usuario'
    ];
}
