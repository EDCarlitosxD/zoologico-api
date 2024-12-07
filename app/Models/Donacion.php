<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donacion extends Model
{

    use HasFactory;


    protected $table = "donaciones";

    protected $fillable = [
        'id_usuario',
        'monto',
        'email',
        'recibo',
        'fecha'
    ];
}
