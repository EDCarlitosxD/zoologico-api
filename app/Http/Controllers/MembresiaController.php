<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class MembresiaController
{
    //*RUTAS
    public function getAll(Request $request){
        $query = Membresia::query();
        $activo = $request->input('estado','');
        if($request->has('estado')){
            $query->where('estado',$activo);
        }
        return response($query->get(),Response::HTTP_OK);
    }

    public function getById($id){
        return response(Membresia::findOrFail($id), Response::HTTP_OK);
    }

    public function guardar(Request $request){
        $validatedData = $request->validate([
            "nombre" => "required|max:255",
            "precio" => "required|numeric",
            "descripcion" => "required",
            "imagen" => "required"
        ]);
        try {
            // Procesar las imágenes
            //*$validatedData['imagen'] = $request->file('imagen')->store('Animales', 'public');
            $membresia = Membresia::create($validatedData);

            return response()->json(['message' => 'Membresia guardada con éxito', 'Membresia' => $membresia], 201);
        } catch (\Exception $error) {
            return response()->json(['error' => 'Error al guardar la membresia: ' . $error->getMessage()], 400);
        }
    }

    public function actualizar(Request $request, $id){
        $validatedData = $this->validateData($request);

        $membresia = Membresia::findOrFail($id);
        $membresia->fill($validatedData);

        
        $membresia->update($request->all());
        return response($membresia, Response::HTTP_OK);
    }

    public function actualizarEstado(Request $request, $id){
        $request->validate([
            'estado'=>'required|boolean',
        ]);

        $membresia = Membresia::findOrFail($id);

        $membresia->estado = $request->input('estado');
        $membresia->save();

        return response()->json(['message' => 'Membresia estado actualizado con exito']);
    }



    //!funciones privadas
    private function validateData(Request $request){
        $validatedData = $request->validate([
            "nombre" => "required|max:255",
            "precio" => "required",
            "descripcion" => "required",
            "imagen" => "required"
        ]);

        return $validatedData;
    }

    
}
