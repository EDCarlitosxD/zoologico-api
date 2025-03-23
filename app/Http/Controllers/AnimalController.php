<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @OA\Tag(
 *     name="Animales",
 *     description="APIs para la gestión de animales en el zoológico"
 * )
 */
class AnimalController
{
    /**
     * @OA\Get(
     *     path="/animales/card/",
     *     tags={"Animales"},
     *     summary="Obtener la lista de animales con imágenes principales",
     *     description="Obtiene una lista de animales, permitiendo búsqueda y filtrado por tipo. Se puede ordenar alfabéticamente.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="datomin",
     *         in="query",
     *         description="Búsqueda parcial en el nombre, nombre científico o tipo.",
     *         required=false,
     *         @OA\Schema(type="string", example="Tigre")
     *     ),
     *     @OA\Parameter(
     *         name="tipo",
     *         in="query",
     *         description="Filtrar por tipo de animal.",
     *         required=false,
     *         @OA\Schema(type="string", example="Mamífero")
     *     ),
     *     @OA\Parameter(
     *         name="orden",
     *         in="query",
     *         description="Ordenar alfabéticamente (A-Z).",
     *         required=false,
     *         @OA\Schema(type="string", enum={"A-Z"})
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Cantidad de resultados por página.",
     *         required=false,
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de animales obtenida correctamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="nombre", type="string", example="Tigre de Bengala"),
     *                 @OA\Property(property="imagen_principal", type="string", example="https://example.com/tigre.jpg"),
     *                 @OA\Property(property="tipo", type="string", example="Mamífero"),
     *                 @OA\Property(property="peso", type="string", example="220kg"),
     *                 @OA\Property(property="altura", type="string", example="1.1m"),
     *                 @OA\Property(property="nombre_cientifico", type="string", example="Panthera tigris tigris"),
     *                 @OA\Property(property="slug", type="string", example="tigre-de-bengala")
     *             )
     *         )
     *     )
     * )
     */
    public function ImgAnimal(Request $request)
    {
        $query = Animal::query()->where('estado',1);
        $datomin = strtolower($request->input('datomin', ''));

        if (!empty($datomin)) {
            $query->where(function ($q) use ($datomin) {
                $q->where('nombre', 'LIKE', "%{$datomin}%")
                    ->orWhere('nombre_cientifico', 'LIKE', "%{$datomin}%")
                    ->orWhere('tipo', 'LIKE', "{$datomin}%");
            });
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->input('tipo'));
        }

        if ($request->has('orden') && $request->input('orden') === 'A-Z') {
            $query->orderBy('nombre', 'asc');
        }

        $animales = $query->select('nombre', 'imagen_principal', 'tipo', 'peso', 'altura', 'nombre_cientifico', 'slug')
            ->paginate($request->input('per_page', 12));

        foreach ($animales as $animal) {
            $animal->imagen_principal = asset('storage') . '/' . ($animal->imagen_principal);
        }

        return response()->json($animales);
    }
    /**
     * @OA\Get(
     *     path="/animales/{slug}",
     *     summary="Obtener información de un animal por slug",
     *     tags={"Animales"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Información del animal obtenida con éxito"),
     *     @OA\Response(response=404, description="Animal no encontrado")
     * )
     */
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
    /**
     * @OA\Get(
     *     path="/animales",
     *     summary="Obtener la lista de todos los animales",
     *     tags={"Animales"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista de animales obtenida con éxito")
     * )
     */
    public function getAll(Request $request)
    {
        $animales = [];
        if (empty($request->input('buscar'))) {
            $animales = Animal::all();
        } else {
            $animales = Animal::where('nombre', 'LIKE', '%' . $request->input('buscar') . '%')->get();
        }


        // Iteramos sobre los animales y agregamos la URL completa para las imágenes
        foreach ($animales as $animal) {
            $animal->imagen_principal = asset('storage') . '/' . ($animal->imagen_principal);
            $animal->imagen_secundaria = asset('storage') . '/' . ($animal->imagen_secundaria);
            $animal->img_ubicacion = asset('storage') . '/' . ($animal->img_ubicacion);
        }

        return response()->json($animales);
    }


    /**
     * @OA\Post(
     *     path="/animales/guardar",
     *     summary="Registrar un nuevo animal",
     *     tags={"Animales"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "nombre_cientifico", "tipo", "imagen_principal"},
     *             @OA\Property(property="nombre", type="string", example="Tigre de Bengala"),
     *             @OA\Property(property="nombre_cientifico", type="string", example="Panthera tigris tigris"),
     *             @OA\Property(property="imagen_principal", type="string", format="binary", example="https://example.com/image.jpg"),
     *             @OA\Property(property="imagen_secundaria", type="string", format="binary", example="https://example.com/image.jpg"),
     *             @OA\Property(property="caracteristicas_fisicas", type="string", example="Características físicas del tigre de Bengala..."),
     *             @OA\Property(property="dieta", type="string", example="Dieta del tigre de Bengala..."),
     *             @OA\Property(property="datos_curiosos", type="string" , example="Datos curiosos del tigre de Bengala..."),
     *             @OA\Property(property="comportamiento", type="string" , example="Comportamiento del tigre de Bengala..."),
     *             @OA\Property(property="peso", type="string" , example="Peso del tigre de Bengala..."),
     *             @OA\Property(property="altura", type="string" , example="Altura del tigre de Bengala..."),
     *             @OA\Property(property="tipo", type="string" , example="Terrestre"),
     *             @OA\Property(property="habitat", type="string" , example="Habitat del tigre de Bengala..."),
     *             @OA\Property(property="descripcion", type="string" , example="Descripcion del tigre de Bengala..."),
     *             @OA\Property(property="subtitulo", type="string" , example="Subtitulo del tigre de Bengala..."),
     *             @OA\Property(property="qr", type="string" , example="qr.png"),
     *             @OA\Property(property="img_ubicacion", type="string", format="binary" , example="https://example.com/image.jpg")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Animal guardado con éxito"),
     *     @OA\Response(response=400, description="Error en la validación")
     * )
     */
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
                //'qr'=> 'required|max:255',
                //'estado' => 'required|boolean',
                'img_ubicacion' => 'required',
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
    /**
     * @OA\Put(
     *     path="/animales/eliminar/{id}",
     *     summary="Desactivar un animal",
     *     tags={"Animales"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"estado"},
     *             @OA\Property(property="estado", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Estado actualizado con éxito")
     * )
     */
    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|boolean',
        ]);

        $animal = Animal::findOrFail($id);

        $animal->estado = $request->input('estado');
        $animal->save();

        return response()->json(['message' => 'animal estado actualizado con exito']);
    }
    /**
     * @OA\Put(
     *     path="/animales/actualizar/{id}",
     *     summary="Actualizar los datos de un animal",
     *     tags={"Animales"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             allOf={@OA\Schema(ref="#/components/schemas/Animal")},
     *             @OA\Property(property="id", not=@OA\Property())
     *         )
     *     ),
     *     @OA\Response(response=200, description="Animal actualizado con éxito")
     * )
     */
    public function actualizar(Request $request, $id)
    {
        // Validar los datos del request
        $validatedData = $request->validate([
            'nombre' => 'required|unique:animales,nombre,' . $id . '|max:80',
            'nombre_cientifico' => 'required|max:150',
             'slug' => 'required|max:255',
            'imagen_principal' => 'nullable',
            'imagen_secundaria' => 'nullable',
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
            'img_ubicacion' => 'nullable',
        ]);

        // Buscar el animal
        $animal = Animal::findOrFail($id);
         $img_principal = $animal->imagen_principal;
         $img_secundaria = $animal->imagen_secundaria;
         $img_ubicacion = $animal->img_ubicacion;
        $animal->fill($validatedData);


        // Manejar la imagen principal
        if ($request->hasFile('imagen_principal')) {
            // Eliminar la imagen anterior
            if ($animal->imagen_principal && Storage::exists($animal->imagen_principal)) {
                Storage::delete($animal->imagen_principal);
            }

            // Guardar la nueva imagen
            $path = $request->file('imagen_principal')->store('Animales', 'public');
            $animal->imagen_principal = $path;
        }

        // Manejar la imagen secundaria
        if ($request->hasFile('imagen_secundaria')) {
            // Eliminar la imagen anterior
            if ($animal->imagen_secundaria && Storage::exists($animal->imagen_secundaria)) {
                Storage::delete($animal->imagen_secundaria);
            }

            // Guardar la nueva imagen
            $path = $request->file('imagen_secundaria')->store('Animales', 'public');
            $animal->imagen_secundaria = $path;
        }

        if ($request->hasFile('img_ubicacion')) {
            // Eliminar la imagen anterior
            if ($animal->img_ubicacion && Storage::exists($animal->img_ubicacion)) {
                Storage::delete($animal->img_ubicacion);
            }
            // Guardar la nueva imagen
            $path = $request->file('img_ubicacion')->store('Animales', 'public');
            $animal->img_ubicacion = $path;
        }

        // Actualizar el resto de los datos
        $animal->save();

        return response()->json(['message' => 'Animal actualizado con éxito', 'animal' => $animal]);
    }
}
