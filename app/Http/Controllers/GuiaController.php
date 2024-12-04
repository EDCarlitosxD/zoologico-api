<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Guia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GuiaController
{
    //

    public function getAll(Request $request){
        $query = Guia::query();
        $activo = $request->input('estado','');

        if($request->has('estado')){
            $query->where('estado',$activo);
        }

        return response($query->get(),Response::HTTP_OK);
    }


    public function getById($id){
        return Guia::findOrFail($id);
    }


    public function save(Request $request){
        $request->validate([
            'nombre_completo' => 'required|max:200',
            "estado" => "required|boolean",
            "disponible" => "required|boolean"
        ]);

        $datos = $request->all();

        $boleto = new Guia($datos);
        $boleto->save();
        return response(["guia" => $boleto],Response::HTTP_CREATED);
    }


    public function actualizar(Request $request,$id){
        $request->validate([
            'nombre_completo' => 'max:200',
            "estado" => "boolean",
            // "disponible" => "boolean"
        ]);

        $guia = Guia::find($id);

        // Verificar si el registro existe
        if (!$guia) {
            return response()->json(['mensaje' => 'Guía no encontrada'], Response::HTTP_NOT_FOUND);
        }

        // Actualizar los campos del registro
        $guia->fill($request->all());

        // Guardar los cambios en la base de datos
        $guia->save();

        return response(["guia" => $guia],Response::HTTP_CREATED);
    }


    public function actualizarEstado(Request $request, $id){
        $request->validate([
            'estado'=>'required|boolean',
        ]);

        $guia = Guia::findOrFail($id);

        $guia->estado = $request->input('estado');
        $guia->save();

        return response()->json(['message' => 'Guia estado actualizado con exito']);

    }

}
