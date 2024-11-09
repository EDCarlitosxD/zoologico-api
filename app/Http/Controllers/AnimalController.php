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
    public function ImgAnimal($dato){
        
        $datomin= strtolower($dato);
        $animales = Animal::select('nombre', 'imagen_principal') //peso tipo altura nombrecientifico nombre imagen
        ->where('nombre', 'LIKE', "{$datomin}%")
        -> orWhere('nombre_cientifico', 'LIKE', "{$datomin}%")
        ->orWhere('habitat', 'LIKE', "{$datomin}%")->get();

        return response()->json($animales);
        
        /*$animal = Animal::select('nombre', 'imagen_principal')->where('nombre',$dato)
        ->orWhere('nombre_cientifico', $dato)
        ->first();*/
        /*
        $animales = Animal::all();
        $resultado = null;
    
        foreach($animales as $animal) {
            if($animal->nombre == $dato || $animal->nombre_cientifico == $dato || $animal->habitat == $dato || $animal->tipo == $dato) {
                //$resultado = $animal;
                return response()->json([
                    'nombre' => $animal->nombre,
                    'imagen_principal' => $animal->imagen_principal

                ]);
                break; 
            }
        }*/
    

        //->simplePaginate(2)
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
            'eliminado' => 'required|boolean',
            'tipo_animal_id' => 'required',

            /*
            'nombre' => 'required|unique:animales|max:255',
            'nombre_cientifico' => 'required|max:255',
            'caracteristicas_fisicas' => 'required',
            'dieta' => 'required',
            'datos_curiosos' => 'required',
            'comportamiento' => 'required',
            'informacion' => 'required',
            'imagen_principal' => 'required',
            'imagen_secundaria' => 'required',
            'activo' => 'required|boolean',
            'tipo_animal_id' => 'required',*/
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
            'activo'=>'required|boolean',
        ]);

        $animal = Animal::findOrFail($id);

        $animal->activo = $request->input('activo');
        $animal->save();

        return response()->json(['message' => 'Animal eliminado con exito']);
    }

    public function actualizar(Request $request, $id){
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
            'eliminado' => 'required|boolean',
            'tipo_animal_id' => 'required',

            /*
            'nombre' => 'required|unique:animales|max:255',
            'nombre_cientifico' => 'required|max:255',
            'caracteristicas_fisicas' => 'required',
            'dieta' => 'required',
            'datos_curiosos' => 'required',
            'comportamiento' => 'required',
            'informacion' => 'required',
            'imagen_principal' => 'required',
            'imagen_secundaria' => 'required',
            'activo' => 'required|boolean',
            'tipo_animal_id' => 'required',*/
        ]);

        $animal=Animal::findOrFail($id);
        $animal->save();

        return response()->json(['message'=>'Animal actualizado con exito']);
    }
}
