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
        'duracion',
        'descripcion',
        'descripcion_incluye',
        'descripcion_importante_reservar',
        'img_recorrido',
        'estado'
    ];
}
