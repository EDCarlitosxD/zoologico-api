<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarjeta extends Model
{

    use HasFactory;

    protected $fillable = [
        'fecha_expiracion',
        'banco',
        'numero_tarjeta',
        'nombre_tarjeta',
        'cvv',
        'tipo_tarjeta',
        'id_usuario'
    ];
}
