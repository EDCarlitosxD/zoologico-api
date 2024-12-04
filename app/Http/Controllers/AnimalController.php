<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AnimalController
{
    public function ImgAnimal(Request $request){

        // Crear un query base
        $query = Animal::query();

        // Obtener el parámetro 'dato' (búsqueda general)
        $datomin = strtolower($request->input('dato', ''));

        if (!empty($datomin)) {
            $query->where(function ($q) use ($datomin) {
                $q->where('nombre', 'LIKE', "{$datomin}%")
                ->orWhere('nombre_cientifico', 'LIKE', "{$datomin}%")
                ->orWhere('habitat', 'LIKE', "{$datomin}%");
            });
        }

        // Filtrar por tipo si el parámetro está presente
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->input('tipo'));
        }

        // Aplicar paginación (10 por página por defecto)
        $animales = $query->select('nombre', 'imagen_principal', 'tipo','peso','altura','nombre_cientifico','slug')
                        ->paginate($request->input('per_page', 12));

        return response()->json($animales);
    }

    public function animalslug($slug){

        $buscar = Animal::where('slug', $slug)->first();

        if(!$buscar){
            throw new NotFoundHttpException('No existe');

        } else{
            return response()->json($buscar);
        }


    }

    public function guardar(Request $request){
         $request->validate([

            'nombre' => 'required|unique:animales|max:80',
            'nombre_cientifico' => 'required|max:150',
            'slug' => 'required|max:255',
            'imagen_principal' => 'required|max:255',
            'imagen_secundaria' => 'required|max:255',
            'caracteristicas_fisicas' => 'required',
            'dieta' => 'required',
            'datos_curiosos' => 'required',
            'comportamiento' => 'required',
            'peso' => 'required|max:45',
            'altura' => 'required|max:45',
            'tipo' => 'required',
            'habitat' => 'required|max:255',
            'descripcion' => 'required',
            'subtitulo' => 'required|max:255',
            'qr'=> 'required|max:255',
            'estado' => 'required|boolean',
            'img_ubicacion' => 'required|max:255',
            ]
        );

        try{
            $datos = $request->except(['slug']);
            $datos['slug'] = Str::slug($request->nombre);
            $datos['imagen_principal']= $request->file('imagen_principal')->store('Animales', 'public');
            $animal = new Animal($datos);
            $animal-> save();
        } catch (Exception $error){
            throw new BadRequestException("Datos llenados incorrectamente:" . $error->getMessage());
        }

    }

    public function actualizarEstado(Request $request, $id){
        $request->validate([
            'estado'=>'required|boolean',
        ]);

        $animal = Animal::findOrFail($id);

        $animal->estado = $request->input('estado');
        $animal->save();

        return response()->json(['message' => 'Animal eliminado con exito']);
    }

    public function actualizar(Request $request, $id){
        $validacion = $request->validate([

            'nombre' => 'required|unique:animales,nombre,' . $id . '|max:80',
            'nombre_cientifico' => 'required|max:150',
            'slug' => 'required|max:255',
            'imagen_principal' => 'required|max:255',
            'imagen_secundaria' => 'required|max:255',
            'caracteristicas_fisicas' => 'required',
            'dieta' => 'required',
            'datos_curiosos' => 'required',
            'comportamiento' => 'required',
            'peso' => 'required|max:45',
            'altura' => 'required|max:45',
            'tipo' => 'required',
            'habitat' => 'required|max:255',
            'descripcion' => 'required',
            'subtitulo' => 'required|max:255',
            'qr'=> 'required|max:255',
            'estado' => 'required|boolean',
            'img_ubicacion' => 'required|max:255',
        ]);

        $animal=Animal::findOrFail($id);
        $animal->update($validacion);

        return response()->json(['message'=>'Animal actualizado con exito']);
    }
}
