<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VistaVentasGeneral extends Model
{
    public $timestamps = false;


    protected $table = 'ventas_general';


    protected $fillable = [
        'id',
        'titulo',
        'precio_total',
        'cantidad'
    ];
}
