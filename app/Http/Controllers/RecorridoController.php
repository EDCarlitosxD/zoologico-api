<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Recorrido;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RecorridoController
{
    //
    public function getAllRecorridosActive(){
        return response(Recorrido::where('estado',1)->get(), Response::HTTP_OK);
    }


}
