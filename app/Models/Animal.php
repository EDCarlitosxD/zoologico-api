<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
