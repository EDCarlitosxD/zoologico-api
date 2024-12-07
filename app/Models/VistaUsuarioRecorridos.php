<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VistaUsuarioRecorridos extends Model
{
    public $timestamps = false;


    protected $table = 'compra_usuario_recorridos';


    protected $fillable = [
        'id_usuario',
        'titulo',
        'fecha',
        'cantidad',
        'precio_total',
        'token'
    ];
}
