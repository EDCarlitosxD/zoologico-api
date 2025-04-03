<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
/**
 * @OA\Schema(
 *     schema="VentaBoletos",
 *     title="Venta de Boletos",
 *     description="Modelo de Venta de Boletos",
 *     required={"id_boleto", "id_usuario", "fecha", "cantidad", "token", "email", "precio_total"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="id_boleto", type="integer", example=10),
 *     @OA\Property(property="id_usuario", type="integer", example=123),
 *     @OA\Property(property="fecha", type="string", format="date", example="2025-03-12"),
 *     @OA\Property(property="cantidad", type="integer", example=3),
 *     @OA\Property(property="token", type="string", example="xyz789abc"),
 *     @OA\Property(property="email", type="string", example="usuario@example.com"),
 *     @OA\Property(property="precio_total", type="number", format="float", example=45.99)
 * )
 */
class venta_boletos extends Model
{
    use HasFactory;
    protected $table = "venta_boletos";

    protected $fillable =
    [
        'id_boleto',
        'id_usuario',
        'fecha',
        'cantidad',
        'token',
        'email',
        'precio_total'
    ];


    public function venta_boletos()
    {
        return $this->hasMany(Boletos::class);
    }
}
