<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="MembresiasUsuarios",
 *     title="MembresiasUsuarios",
 *     description="Modelo de MembresiasUsuarios",
 *     required={"id_membresia", "id_usuario", "fecha_compra", "meses", "fecha_vencimiento", "precio_total", "token", "email"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="id_membresia", type="integer", example=1),
 *     @OA\Property(property="id_usuario", type="integer", example=123),
 *     @OA\Property(property="fecha_compra", type="string", format="date", example="2025-03-12"),
 *     @OA\Property(property="meses", type="integer", example=3),
 *     @OA\Property(property="fecha_vencimiento", type="string", format="date", example="2025-06-12"),
 *     @OA\Property(property="precio_total", type="integer", example=1000),
 *     @OA\Property(property="token", type="string", example="xyz789abc"),
 *     @OA\Property(property="email", type="string", example="usuario@example.com")
 * )
 */
class MembresiasUsuarios extends Model
{
    use HasFactory;
    protected $table = "membresias_usuarios";
    protected $fillable =
    [
        'id_membresia',
        'id_usuario',
        'fecha_compra',
        'meses',
        'fecha_vencimiento',
        'precio_total',
        'token',
        'email',
    ];
    public function venta_membresias()
    {
        return $this->hasMany(Membresia::class);
    }
}
