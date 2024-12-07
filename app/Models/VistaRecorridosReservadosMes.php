<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VistaRecorridosReservadosMes extends Model
{
    public $timestamps = false;


    protected $table = 'recorridos_vendidos_mes';


    protected $fillable = [
        'titulo',
        'ventas'
    ];
}
