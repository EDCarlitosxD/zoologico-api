<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boletos extends Model
{
    use HasFactory;
    protected $table = "boletos";

    protected $fillable =
    [
        'titulo',
        'descripcion',
        'precio',
        'estado',
        'imagen',
        "descripcion_card",
        "advertencias"
    ];
}
