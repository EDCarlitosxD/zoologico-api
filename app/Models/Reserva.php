<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{

    use HasFactory;
    protected $fillable = [
        'id_usuario',
        'cantidad_personas',
        'id_horario_recorrido',
        'fecha',
        'token'
    ];
}
