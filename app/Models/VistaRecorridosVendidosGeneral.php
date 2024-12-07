<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VistaRecorridosVendidosGeneral extends Model
{
    public $timestamps = false;


    protected $table = 'recorridos_vendidos_general';


    protected $fillable = [
        'titulo',
        'ventas',
    ];
}
