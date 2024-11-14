<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;
    protected $table = "animales";


    public function tipo(){
        return $this->belongsTo(TipoAnimal::class,'tipo_animal_id');
    }
}
