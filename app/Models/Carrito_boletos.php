<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito_boletos extends Model
{
    use HasFactory;
    protected $table = "carrito_boletos";

    protected $fillable = 
    [
        'cantidad',
        'id_boleto',
        'id_usuario'
    ];
}

