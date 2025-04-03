<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Membresia",
 *     title="Membresia",
 *     description="Modelo de Membresía",
 *     required={"nombre", "precio", "descripcion", "imagen"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Premium"),
 *     @OA\Property(property="precio", type="number", format="float", example=99.99),
 *     @OA\Property(property="descripcion", type="string", example="Acceso premium por un año"),
 *     @OA\Property(property="imagen", type="string", example="http://example.com/membresia_premium.jpg"),
 *     @OA\Property(property="estado", type="boolean", example=true)
 * )
 */
class Membresia extends Model
{
    use HasFactory;

    protected $table = "membresias";

    protected $fillable = [
        'nombre',
        'precio',
        'imagen',
        'entradas_ilimitadas',
        'descuento_alimentos_souvenirs',
        'acceso_eventos',
        'descuento_tours',
        'experiencias_animales',
        'estacionamiento_preferencial',
        'detras_camaras',
        'recorrido_vip_gratuito',
        'programas_conservacion',
        'descuento_renta_espacios_eventos',
        'precio_especial_invitados',
        'regalo_bienvenida',
        'charlas_educativas',
        'estado'
    ];
}
