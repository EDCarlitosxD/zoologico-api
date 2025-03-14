<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{

    use HasFactory;
    protected $fillable = [
        'id_usuario',
        'cantidad',
        'id_horario_recorrido',
        'precio_total',
        'fecha',
        'token'
    ];
}
