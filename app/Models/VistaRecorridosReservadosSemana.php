<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VistaRecorridosReservadosSemana extends Model
{
    public $timestamps = false;


    protected $table = 'recorridos_vendidos_semana';


    protected $fillable = [
        'titulo',
        'ventas'
    ];
}