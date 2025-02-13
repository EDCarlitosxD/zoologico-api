<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insignias;
use Illuminate\Http\Response;

class InsigniasController
{
    //
    public function getAll(Request $request){
        $query = Insignias::query();
        $activo = $request->input('estado','');
        if($request->has('estado')){
            $query->where('estado',$activo);
        }
        return response($query->get(),Response::HTTP_OK);
    }
}
