<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioRecorrido extends Model
{

    use HasFactory;

    protected $fillable = [
        'horario_inicio',
        'disponible',
        'id_recorrido',
        'id_guia',
        'fecha',
        'horario_fin'
    ];
}
