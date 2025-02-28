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

    public function getById($id){
        return response(Insignias::findOrFail($id),Response::HTTP_OK);
    }


    public function guardar(Request $request) {

        $validatedData = $request->validate([
            "nombre" => "required|max:255",
            "imagen" => "required",
            "cantidad" => "required|numeric"
        ]);

        try {
            // Procesar las imágenes
            //*$validatedData['imagen'] = $request->file('imagen')->store('Animales', 'public');



            
            $validatedData['estado'] = 1;
            $insignia = Insignias::create($validatedData);
            return response()->json(['message' => 'Insignia guardada con éxito', 'Insignia' => $insignia], 201);
        } catch (\Exception $error) {
            return response()->json(['error' => 'Error al guardar el la insignia: ' . $error->getMessage()], 400);
        }
    }

    public function actualizar(Request $request, $id){

        $request->validate([
            "nombre" => "required|max:255",
            "imagen" => "required",
            "cantidad" => "required|numeric"
        ]);

        $insignia = Insignias::findOrFail($id);
        //*$validatedData['imagen'] = $request->file('imagen')->store('Animales', 'public');

        $insignia->fill($request->all());
        $insignia->update();
        return response(["insignia" => $insignia],Response::HTTP_CREATED);
    }

    public function actualizarEstado(Request $request, $id){
        $request->validate([
            'estado'=>'required|boolean',
        ]);

        $insignia = Insignias::findOrFail($id);

        $insignia->estado = $request->input('estado');
        $insignia->save();

        return response()->json(['message' => 'Insignia estado actualizado con exito']);
    }









}
