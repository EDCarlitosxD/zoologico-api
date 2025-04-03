<?php
//*DOCUMENTADO
namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\User;
use App\Notifications\NuevoAnimalAgregado;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function PHPSTORM_META\type;

/**
 * @OA\Tag(
 *     name="Animales",
 *     description="APIs para gestionar Animales"
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
        $query = Animal::query()->where('estado', 1);
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
     *     summary="Obtener información de un animal por su slug",
     *     description="Retorna la información de un animal basado en su slug único.",
     *     operationId="getAnimalBySlug",
     *     tags={"Animales"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Slug único del animal",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos del animal encontrados",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Animal"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Animal no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No existe")
     *         )
     *     )
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
     *     summary="Obtiene todos los animales o filtra por nombre",
     *     description="Devuelve una lista de animales. Si se proporciona un parámetro de búsqueda, filtra los resultados por el nombre del animal.",
     *     operationId="getAllAnimales",
     *     tags={"Animales"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de animales obtenida exitosamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 ref="#/components/schemas/Animal"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Parámetro de búsqueda incorrecto"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
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
     *     path="/animales",
     *     summary="Guarda un nuevo animal en la base de datos",
     *     description="Este endpoint permite guardar un nuevo animal en la base de datos con la información proporcionada, incluyendo imágenes y otros detalles.",
     *     operationId="guardarAnimal",
     *     tags={"Animales"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del animal a guardar",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"nombre", "nombre_cientifico", "imagen_principal", "imagen_secundaria", "caracteristicas_fisicas", "dieta", "datos_curiosos", "comportamiento", "peso", "altura", "tipo", "habitat", "descripcion", "subtitulo", "img_ubicacion"},
     *                 @OA\Property(
     *                     property="nombre",
     *                     type="string",
     *                     maxLength=80,
     *                     description="Nombre común del animal",
     *                     example="León"
     *                 ),
     *                 @OA\Property(
     *                     property="nombre_cientifico",
     *                     type="string",
     *                     maxLength=150,
     *                     description="Nombre científico del animal",
     *                     example="Panthera leo"
     *                 ),
     *                 @OA\Property(
     *                     property="imagen_principal",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen principal del animal"
     *                 ),
     *                 @OA\Property(
     *                     property="imagen_secundaria",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen secundaria del animal"
     *                 ),
     *                 @OA\Property(
     *                     property="caracteristicas_fisicas",
     *                     type="string",
     *                     description="Características físicas del animal",
     *                     example="Gran felino con melena en los machos, cuerpo musculoso y pelaje amarillo dorado."
     *                 ),
     *                 @OA\Property(
     *                     property="dieta",
     *                     type="string",
     *                     description="Dieta del animal",
     *                     example="Carnívoro, se alimenta de cebras, antílopes y otros mamíferos."
     *                 ),
     *                 @OA\Property(
     *                     property="datos_curiosos",
     *                     type="string",
     *                     description="Datos curiosos sobre el animal",
     *                     example="Los leones pueden dormir hasta 20 horas al día."
     *                 ),
     *                 @OA\Property(
     *                     property="comportamiento",
     *                     type="string",
     *                     description="Comportamiento del animal",
     *                     example="Viven en manadas lideradas por hembras y un macho dominante."
     *                 ),
     *                 @OA\Property(
     *                     property="peso",
     *                     type="string",
     *                     description="Peso del animal",
     *                     example="190 kg (machos), 130 kg (hembras)"
     *                 ),
     *                 @OA\Property(
     *                     property="altura",
     *                     type="string",
     *                     description="Altura del animal",
     *                     example="1.2 metros en la cruz"
     *                 ),
     *                 @OA\Property(
     *                     property="tipo",
     *                     type="string",
     *                     description="Tipo de animal",
     *                     example="Terrestre"
     *                 ),
     *                 @OA\Property(
     *                     property="habitat",
     *                     type="string",
     *                     maxLength=255,
     *                     description="Hábitat del animal",
     *                     example="Sabana africana y pastizales abiertos."
     *                 ),
     *                 @OA\Property(
     *                     property="descripcion",
     *                     type="string",
     *                     description="Descripción del animal",
     *                     example="El león es uno de los felinos más grandes y poderosos, conocido por su melena y su vida en manada."
     *                 ),
     *                 @OA\Property(
     *                     property="subtitulo",
     *                     type="string",
     *                     maxLength=255,
     *                     description="Subtítulo del animal",
     *                     example="El rey de la selva"
     *                 ),
     *                 @OA\Property(
     *                     property="img_ubicacion",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen de la ubicación del animal"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Animal guardado con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Animal guardado con éxito"
     *             ),
     *             @OA\Property(
     *                 property="animal",
     *                 type="object",
     *                 description="Datos del animal guardado"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al guardar el animal",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Error al guardar el animal: Mensaje de error"
     *             )
     *         )
     *     )
     * )
     */

    public function guardar(Request $request)
    {
        $usuarios = User::all(); //*

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


            foreach ($usuarios as $usuario){
                $usuario->notify(new NuevoAnimalAgregado($validatedData, $usuario));
            }

            // Guardar en la base de datos
            $animal = Animal::create($validatedData);

            return response()->json(['message' => 'Animal guardado con éxito', 'animal' => $animal], 201);
        } catch (\Exception $error) {
            return response()->json(['error' => 'Error al guardar el animal: ' . $error->getMessage()], 400);
        }
    }


    /**
     * @OA\Put(
     *     path="/animales/{id}/actualizar-estado",
     *     summary="Actualiza el estado de un animal",
     *     description="Este endpoint permite actualizar el estado (activo/inactivo) de un animal específico en la base de datos.",
     *     operationId="actualizarEstadoAnimal",
     *     tags={"Animales"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del animal cuyo estado se desea actualizar",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para actualizar el estado del animal",
     *         @OA\JsonContent(
     *             required={"estado"},
     *             @OA\Property(
     *                 property="estado",
     *                 type="boolean",
     *                 description="Nuevo estado del animal (true para activo, false para inactivo)"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estado del animal actualizado con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="animal estado actualizado con exito"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Animal no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Animal no encontrado"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="El campo estado es requerido."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"estado": {"El campo estado es requerido."}}
     *             )
     *         )
     *     )
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
     *     path="/animales/{id}",
     *     summary="Actualiza un animal existente",
     *     description="Este endpoint permite actualizar la información de un animal existente, incluyendo sus imágenes y otros detalles.",
     *     operationId="actualizarAnimal",
     *     tags={"Animales"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del animal que se desea actualizar",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del animal a actualizar",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="nombre",
     *                     type="string",
     *                     maxLength=80,
     *                     description="Nombre común del animal"
     *                 ),
     *                 @OA\Property(
     *                     property="nombre_cientifico",
     *                     type="string",
     *                     maxLength=150,
     *                     description="Nombre científico del animal"
     *                 ),
     *                 @OA\Property(
     *                     property="imagen_principal",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen principal del animal (opcional)"
     *                 ),
     *                 @OA\Property(
     *                     property="imagen_secundaria",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen secundaria del animal (opcional)"
     *                 ),
     *                 @OA\Property(
     *                     property="caracteristicas_fisicas",
     *                     type="string",
     *                     description="Características físicas del animal"
     *                 ),
     *                 @OA\Property(
     *                     property="dieta",
     *                     type="string",
     *                     description="Dieta del animal"
     *                 ),
     *                 @OA\Property(
     *                     property="datos_curiosos",
     *                     type="string",
     *                     description="Datos curiosos sobre el animal"
     *                 ),
     *                 @OA\Property(
     *                     property="comportamiento",
     *                     type="string",
     *                     description="Comportamiento del animal"
     *                 ),
     *                 @OA\Property(
     *                     property="peso",
     *                     type="string",
     *                     maxLength=45,
     *                     description="Peso del animal"
     *                 ),
     *                 @OA\Property(
     *                     property="altura",
     *                     type="string",
     *                     maxLength=45,
     *                     description="Altura del animal"
     *                 ),
     *                 @OA\Property(
     *                     property="tipo",
     *                     type="string",
     *                     description="Tipo de animal"
     *                 ),
     *                 @OA\Property(
     *                     property="habitat",
     *                     type="string",
     *                     maxLength=255,
     *                     description="Hábitat del animal"
     *                 ),
     *                 @OA\Property(
     *                     property="descripcion",
     *                     type="string",
     *                     description="Descripción del animal"
     *                 ),
     *                 @OA\Property(
     *                     property="subtitulo",
     *                     type="string",
     *                     maxLength=255,
     *                     description="Subtítulo del animal"
     *                 ),
     *                 @OA\Property(
     *                     property="img_ubicacion",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen de la ubicación del animal (opcional)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Animal actualizado con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Animal actualizado con éxito"
     *             ),
     *             @OA\Property(
     *                 property="animal",
     *                 type="object",
     *                 description="Datos del animal actualizado"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Animal no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Animal no encontrado"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="El campo nombre es requerido."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"nombre": {"El campo nombre es requerido."}}
     *             )
     *         )
     *     )
     * )
     */

    public function actualizar(Request $request, $id)
    {
        // Validar los datos del request
        $validatedData = $request->validate([
            'nombre' => 'required|unique:animales,nombre,' . $id . '|max:80',
            'nombre_cientifico' => 'required|max:150',
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
            'img_ubicacion' => 'nullable',
        ]);
        
        // Buscar el animal
        $animal = Animal::findOrFail($id);

        // Asignar los valores validados
        $animal->fill($validatedData);
        // Generar el slug a partir del nombre
        $animal->slug = Str::slug($request->nombre);

        if(is_string($request->imagen_principal)){
            $animal->imagen_principal = Animal::findOrFail($id)->imagen_principal;
        } else if ($request->hasFile('imagen_principal')) {
            if ($animal->imagen_principal && Storage::exists($animal->imagen_principal)) {
                Storage::delete($animal->imagen_principal);
            }
            $animal->imagen_principal = $request->file('imagen_principal')->store('Animales', 'public');
        }
        
        if(is_string($request->imagen_secundaria)){
            $animal->imagen_secundaria = Animal::findOrFail($id)->imagen_secundaria;
        } else if ($request->hasFile('imagen_secundaria')) {
            if ($animal->imagen_secundaria && Storage::exists($animal->imagen_secundaria)) {
                Storage::delete($animal->imagen_secundaria);
            }
            $animal->imagen_secundaria = $request->file('imagen_secundaria')->store('Animales', 'public');
        }
        
        if(is_string($request->img_ubicacion)){
            $animal->img_ubicacion = Animal::findOrFail($id)->img_ubicacion;
        } else if ($request->hasFile('img_ubicacion')) {
            if ($animal->img_ubicacion && Storage::exists($animal->img_ubicacion)) {
                Storage::delete($animal->img_ubicacion);
            }
            $animal->img_ubicacion = $request->file('img_ubicacion')->store('Animales', 'public');
        }
        $animal->save();
        

        return response()->json(['message' => 'Animal actualizado con éxito', 'animal' => $animal]);
    }
}
