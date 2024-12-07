<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VistaBoletosVendidosGeneral extends Model
{
    public $timestamps = false;


    protected $table = 'boletos_vendidos_general';


    protected $fillable = [
        'titulo',
        'cantidad',
    ];
}
