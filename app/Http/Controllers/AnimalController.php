<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\TipoAnimal;
use App\Models\User;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    //

    public function holaMundo(){
        return "hola mundo";
    }

    public function getAllTipoAnimales(){
        return TipoAnimal::all();
    }
    
    public function ObtenerUsuarios(){
        $searchid=5;
        return User::where('id', '=',$searchid)->first();
        /*return User::find(5); */
    }

    public function ObtenerImgAnimal(){
        return Animal::select('nombre, imagen_principal');
    }
}
