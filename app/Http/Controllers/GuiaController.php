<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Guia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GuiaController
{
    //

    // public function getAll(Request $request){
    //     $query = Guia::query();
    //     $activo = $request->input('estado','');

    //     if($request->has('estado')){
    //         $query->where('estado',$activo);
    //     }

    //     return response($query->get(),Response::HTTP_OK);
    // }


    // public function getById($id){
    //     return Guia::findOrFail($id);
    // }


    // public function save(Request $request){
    //     $request->validate([
    //         'titulo' => 'required|max:80',
    //         "descripcion" => 'required|max:45',
    //         "precio" => 'required|numeric',
    //         "imagen" => "required|file|mimes:png,jpg"
    //     ]);

    //     $datos = $request->all();
    //     $datos['imagen']= $request->file('imagen')->store('Boletos', 'public');

    //     $boleto = new boletos($datos);
    //     $boleto->save();
    //     $boleto->imagen = asset('storage/'.$boleto->imagen);
    //     return response(["boleto" => $boleto],Response::HTTP_CREATED);
    // }


    // public function delete(Request $request, $id){
    //     $request->validate([
    //         'estado'=>'required|boolean',
    //     ]);

    //     $boleto = boletos::findOrFail($id);

    //     $boleto->estado = $request->input('estado');
    //     $boleto->save();

    //     return response(['message' => 'Animal eliminado con exito'],Response::HTTP_ACCEPTED);

    // }

}
