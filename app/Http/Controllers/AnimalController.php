<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AnimalController
{
    public function ImgAnimal(Request $request)
    {

        // Crear un query base
        $query = Animal::query();
        $query = Animal::query();

        // Obtener el parámetro 'dato' (búsqueda general)
        $datomin = strtolower($request->input('dato', ''));
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
        // Filtrar por tipo si el parámetro está presente
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->input('tipo'));
        }

        // Aplicar paginación (10 por página por defecto)
        $animales = $query->select('nombre', 'imagen_principal', 'tipo', 'peso', 'altura', 'nombre_cientifico', 'slug')
            ->paginate($request->input('per_page', 12));



        // Iteramos sobre los animales y agregamos la URL completa para las imágenes
        foreach ($animales as $animal) {
            $animal->imagen_principal = asset('storage') . '/' . ($animal->imagen_principal);
        }

        return response()->json($animales);
    }

    public function animalslug($slug)
    {

        $buscar = Animal::where('slug', $slug)->first();

        if (!$buscar) {
            throw new NotFoundHttpException('No existe');
        } else {
            $buscar->imagen_principal = asset('storage') . '/' . ($buscar->imagen_principal);
            $buscar->imagen_secundaria = asset('storage') . '/' . ($buscar->imagen_secundaria);
            $buscar->img_ubicacion = asset('storage') . '/' . ($buscar->img_ubicacion);
            return response()->json($buscar);
        }
    }

    public function getAll()
    {
        $animales = Animal::all();

        // Iteramos sobre los animales y agregamos la URL completa para las imágenes
        foreach ($animales as $animal) {
            $animal->imagen_principal = asset('storage') . '/' . ($animal->imagen_principal);
            $animal->imagen_secundaria = asset('storage') . '/' . ($animal->imagen_secundaria);
            $animal->img_ubicacion = asset('storage') . '/' . ($animal->img_ubicacion);
        }

        return response()->json($animales);
    }

    public function guardar(Request $request)
    {
        $validatedData = $request->validate(
            [

                'nombre' => 'required|unique:animales|max:80',
                'nombre_cientifico' => 'required|max:150',
                // 'slug' => 'required|max:255',
                'imagen_principal' => 'required',
                'imagen_secundaria' => 'required',
                'caracteristicas_fisicas' => 'required',
                'dieta' => 'required',
                'datos_curiosos' => 'required',
                'comportamiento' => 'required',
                'peso' => 'required',
                'altura' => 'required',
                'tipo' => 'required',
                'habitat' => 'required|max:255',
                'descripcion' => 'required',
                'subtitulo' => 'required|max:255',
                // 'qr'=> 'required|max:255',
                // 'estado' => 'required|boolean',
                'img_ubicacion' => 'required',

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

        try {
            // Procesar las imágenes
            $validatedData['imagen_principal'] = $request->file('imagen_principal')->store('Animales', 'public');
            $validatedData['imagen_secundaria'] = $request->file('imagen_secundaria')->store('Animales', 'public');
            $validatedData['img_ubicacion'] = $request->file('img_ubicacion')->store('Animales', 'public');
            $validatedData['qr'] = 'qr.png';
            $validatedData['estado'] = 1;
            // Crear el slug basado en el nombre
            $validatedData['slug'] = Str::slug($request->nombre);

            // Guardar en la base de datos
            $animal = Animal::create($validatedData);

            return response()->json(['message' => 'Animal guardado con éxito', 'animal' => $animal], 201);
        } catch (\Exception $error) {
            return response()->json(['error' => 'Error al guardar el animal: ' . $error->getMessage()], 400);
        }
    }

    public function actualizarEstado(Request $request, $id){
        $request->validate([
            'estado'=>'required|boolean',
        ]);

        $animal = Animal::findOrFail($id);

        $animal->estado = $request->input('estado');
        $animal->save();

        return response()->json(['message' => 'animal estado actualizado con exito']);

    }
    public function actualizar(Request $request, $id)
    {
        // Validar los datos del request
        $validatedData = $request->validate([
            'nombre' => 'required|unique:animales,nombre,' . $id . '|max:80',
            'nombre_cientifico' => 'required|max:150',
            // 'slug' => 'required|max:255',
            'imagen_principal' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'imagen_secundaria' => 'nullable|mimes:jpg,jpeg,png|max:2048',
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
            // 'qr' => 'required|max:255',
            // 'estado' => 'required|boolean',
            'img_ubicacion' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Buscar el animal
        $animal = Animal::findOrFail($id);
        // $img_principal = $animal->imagen_principal;
        // $img_secundaria = $animal->imagen_secundaria;
        // $img_ubicacion = $animal->img_ubicacion;
        $animal->fill($validatedData);


        // Manejar la imagen principal
        if ($request->hasFile('imagen_principal')) {
            // Eliminar la imagen anterior
            if ($animal->imagen_principal && Storage::exists($animal->imagen_principal)) {
                Storage::delete($animal->imagen_principal);
            }

            // Guardar la nueva imagen
            $path = $request->file('imagen_principal')->store('public/Animales');
            $animal->imagen_principal = $path;
        }

        // Manejar la imagen secundaria
        if ($request->hasFile('imagen_secundaria')) {
            // Eliminar la imagen anterior
            if ($animal->imagen_secundaria && Storage::exists($animal->imagen_secundaria)) {
                Storage::delete($animal->imagen_secundaria);
            }

            // Guardar la nueva imagen
            $path = $request->file('imagen_secundaria')->store('public/Animales');
            $animal->imagen_secundaria = $path;
        }

        if ($request->hasFile('img_ubicacion')) {
            // Eliminar la imagen anterior
            if ($animal->img_ubicacion && Storage::exists($animal->img_ubicacion)) {
                Storage::delete($animal->img_ubicacion);
            }
            // Guardar la nueva imagen
            $path = $request->file('img_ubicacion')->store('public/Animales');
            $animal->img_ubicacion = $path;
        }

        // Actualizar el resto de los datos
        $animal->save();

        return response()->json(['message' => 'Animal actualizado con éxito', 'animal' => $animal]);
    }
}
