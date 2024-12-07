<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VistaBoletosVendidosYear extends Model
{
    public $timestamps = false;


    protected $table = 'boletos_vendidos_year';


    protected $fillable = [
        'titulo',
        'cantidad',
    ];
}
