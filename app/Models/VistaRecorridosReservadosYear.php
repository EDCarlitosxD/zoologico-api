<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VistaRecorridosReservadosYear extends Model
{
    public $timestamps = false;


    protected $table = 'recorridos_vendidos_year';


    protected $fillable = [
        'titulo',
        'ventas'
    ];
}
