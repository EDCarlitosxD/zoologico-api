<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Animal",
 *     title="Animal",
 *     description="Modelo de Animal",
 *     required={"nombre", "nombre_cientifico", "slug", "estado", "tipo"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="León"),
 *     @OA\Property(property="nombre_cientifico", type="string", example="Panthera leo"),
 *     @OA\Property(property="slug", type="string", example="leon"),
 *     @OA\Property(property="imagen_principal", type="string", example="https://example.com/leon.jpg"),
 *     @OA\Property(property="imagen_secundaria", type="string", example="https://example.com/leon2.jpg"),
 *     @OA\Property(property="caracteristicas_fisicas", type="string", example="Grandes y musculosos"),
 *     @OA\Property(property="dieta", type="string", example="Carnívoro"),
 *     @OA\Property(property="datos_curiosos", type="string", example="Los leones duermen 20 horas al día"),
 *     @OA\Property(property="comportamiento", type="string", example="Viven en manadas"),
 *     @OA\Property(property="peso", type="string", example="190 kg"),
 *     @OA\Property(property="altura", type="string", example="1.2 m"),
 *     @OA\Property(property="habitat", type="string", example="Sabana africana"),
 *     @OA\Property(property="descripcion", type="string", example="El rey de la selva"),
 *     @OA\Property(property="subtitulo", type="string", example="Un felino majestuoso"),
 *     @OA\Property(property="qr", type="string", example="https://example.com/qrcode.png"),
 *     @OA\Property(property="estado", type="boolean", example=true),
 *     @OA\Property(property="tipo", type="string", example="Terrestre"),
 *     @OA\Property(property="img_ubicacion", type="string", example="https://example.com/mapa.jpg")
 * )
 */
class Animal extends Model
{
    use HasFactory;
    protected $table = "animales";

    protected $fillable = 
    [
        'nombre',
        'nombre_cientifico',
        'slug',
        'imagen_principal',
        'imagen_secundaria',
        'caracteristicas_fisicas',
        'dieta',
        'datos_curiosos',
        'comportamiento',
        'peso',
        'altura',
        'habitat',
        'descripcion',
        'subtitulo',
        'qr',
        'estado',
        'tipo',
        'img_ubicacion',
    ];
    
}
