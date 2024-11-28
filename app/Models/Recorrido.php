<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recorrido extends Model
{

    use HasFactory;
    protected $fillable = [
        'titulo',
        'precio',
        'descripcion',
        'duracion',
        'cantidad_personas',
        'precio_persona_extra',
        'img_recorrido'
    ];
}
