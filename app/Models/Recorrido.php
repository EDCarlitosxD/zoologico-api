<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Recorrido",
 *     title="Recorrido",
 *     description="Modelo de Recorrido",
 *     required={"titulo", "precio", "duracion", "descripcion", "img_recorrido"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="titulo", type="string", example="Safari Nocturno"),
 *     @OA\Property(property="precio", type="number", format="float", example=49.99),
 *     @OA\Property(property="duracion", type="string", example="02:00:00"),
 *     @OA\Property(property="descripcion", type="string", example="Un recorrido emocionante por la selva en la noche."),
 *     @OA\Property(property="descripcion_incluye", type="string", nullable=true, example="Guía turístico, refrigerios."),
 *     @OA\Property(property="descripcion_importante_reservar", type="string", nullable=true, example="Reservar con 48 horas de anticipación."),
 *     @OA\Property(property="img_recorrido", type="string", example="https://example.com/safari.jpg"),
 *     @OA\Property(property="estado", type="boolean", example=true)
 * )
 */
class Recorrido extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'precio',
        'duracion',
        'descripcion',
        'descripcion_incluye',
        'descripcion_importante_reservar',
        'img_recorrido',
        'estado'
    ];

    public function horarios()
    {
        return $this->hasMany(HorarioRecorrido::class, 'id_recorrido', 'id');
    }
}
