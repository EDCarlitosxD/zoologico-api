<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class venta_boletos extends Model
{
    use HasFactory;
    protected $table = "venta_boletos";

    protected $fillable = 
    [
        'fecha',
        'cantidad',
        'token',
        'email',
        'id_usuario',
        'id_boleto'

    ];
}
