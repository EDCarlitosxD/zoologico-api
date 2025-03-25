<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Boletos",
 *     title="Boletos",
 *     description="Modelo de Boletos",
 *     required={"titulo", "descripcion", "precio", "estado"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="titulo", type="string", example="Entrada General"),
 *     @OA\Property(property="descripcion", type="string", example="Acceso completo al zoológico"),
 *     @OA\Property(property="precio", type="number", format="float", example=15.99),
 *     @OA\Property(property="estado", type="boolean", example=true),
 *     @OA\Property(property="imagen", type="string", example="https://example.com/boleto.jpg"),
 *     @OA\Property(property="descripcion_card", type="string", example="Disfruta de un día increíble en el zoológico"),
 *     @OA\Property(property="advertencias", type="string", example="No incluye alimentos")
 * )
 */
class Boletos extends Model
{
    use HasFactory;
    protected $table = "boletos";

    protected $fillable =
    [
        'titulo',
        'descripcion',
        'precio',
        'estado',
        'imagen',
        "descripcion_card",
        "advertencias"
    ];
}
