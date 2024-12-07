<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VistaBoletosVendidosSemana extends Model
{
    public $timestamps = false;


    protected $table = 'boletos_vendidos_semana';


    protected $fillable = [
        'titulo',
        'cantidad',
    ];
}
