<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VistaBoletosVendidosMes extends Model
{
    public $timestamps = false;


    protected $table = 'boletos_vendidos_mes';


    protected $fillable = [
        'titulo',
        'cantidad',
    ];
}
