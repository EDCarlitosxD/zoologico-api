<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class venta_boletos extends Model
{
    use HasFactory;
    protected $table = "venta_boletos";

    protected $fillable = 
    [
        'id_boleto',
        'id_usuario',
        'fecha',
        'cantidad',
        'token',
        'email',
        'precio_total'
    ];

}
