<?php

namespace App\Services;

use App\Models\HorarioRecorrido;
use App\Models\Membresia;
use App\Models\Membresias;
use App\Models\Recorrido;
use App\Models\User;
use App\Models\VistaRecorridosReservadosMes;
use App\Models\VistaRecorridosReservadosSemana;
use App\Models\VistaRecorridosReservadosYear;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MembresiaService
{

    public function crearMembresia($request)
    {
        $user = User::all();
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'entradas_ilimitadas' => 'required|boolean',
            'descuento_alimentos_souvenirs' => 'required|numeric',
            'acceso_eventos' => 'required|boolean',
            'descuento_tours' => 'required|numeric', 
            'experiencias_animales' => 'required|boolean',
            'estacionamiento_preferencial' => 'required|boolean',
            'detras_camaras' => 'required|boolean',
            'recorrido_vip_gratuito' => 'required|boolean',
            'programas_conservacion' => 'required|boolean',
            'descuento_renta_espacios_eventos' => 'required|numeric',
            'precio_especial_invitados' => 'required|numeric',
            'regalo_bienvenida' => 'sometimes',
            'charlas_educativas' => 'required|boolean',
            'imagen' => 'required|string', // Puede ser Base64
        ]);
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen'); // Obtiene el archivo
            $imageData = base64_encode(file_get_contents($file->getRealPath())); // Convierte a Base64
            $mimeType = $file->getClientMimeType(); // Obtiene el tipo de archivo (image/png, image/jpeg, etc.)
            $validatedData['imagen'] = "data:$mimeType;base64,$imageData"; // Guardar en Base64 en la BD
        }

        // Procesar imagen Base64
        if (!empty($validatedData['imagen']) && str_starts_with($validatedData['imagen'], 'data:image')) {
            $imageData = explode(',', $validatedData['imagen'])[1];
            $imagePath = 'imagenes/membresias/' . uniqid() . '.png';
            Storage::disk('public')->put($imagePath, base64_decode($imageData));
            $validatedData['imagen'] = $imagePath;
        }

        $Membresia = Membresia::create($validatedData);
        return $Membresia;
    }

    public function actualizarMembresia($request, $id)
    {

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required',
            'entradas_ilimitadas' => 'required|boolean',
            'descuento_alimentos_souvenirs' => 'required|numeric',
            'acceso_eventos' => 'required|boolean',
            'descuento_tours' => 'required|numeric', 
            'experiencias_animales' => 'required|boolean',
            'estacionamiento_preferencial' => 'required|boolean',
            'detras_camaras' => 'required|boolean',
            'recorrido_vip_gratuito' => 'required|boolean',
            'programas_conservacion' => 'required|boolean',
            'descuento_renta_espacios_eventos' => 'required|numeric',
            'precio_especial_invitados' => 'required|numeric',
            'regalo_bienvenida' => 'sometimes',
            'charlas_educativas' => 'required|boolean',
            'imagen' => 'required|string', // Puede ser Base64
        ]);

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen'); // Obtiene el archivo
            $imageData = base64_encode(file_get_contents($file->getRealPath())); // Convierte a Base64
            $mimeType = $file->getClientMimeType(); // Obtiene el tipo de archivo (image/png, image/jpeg, etc.)
            $validatedData['imagen'] = "data:$mimeType;base64,$imageData"; // Guardar en Base64 en la BD
        }
        // Procesar imX|agen Base64
        if (!empty($validatedData['imagen']) && str_starts_with($validatedData['imagen'], 'data:image')) {
            $imageData = explode(',', $validatedData['imagen'])[1];
            $imagePath = 'imagenes/Membresias/' . uniqid() . '.png';
            Storage::disk('public')->put($imagePath, base64_decode($imageData));
            $validatedData['imagen'] = $imagePath;
        } else {
            $validatedData['imagen'] = Membresia::findOrFail($id)->imagen;
        }

        $membresia = Membresia::findOrFail($id);
        $membresia->update($validatedData);
        return $membresia;
    }


    // public function updateDatos($request, $id){
    //     if (!$request->has('horarios') || !is_array($request->horarios)) {
    //         return response()->json(["error" => "El campo horarios es obligatorio y debe ser un array"], 400);
    //     }

    //     $validacion = $request->validate([
    //         'titulo' => 'required|max:45',
    //         'precio' => 'required|numeric',
    //         'duracion' => 'required|date_format:H:i:s',
    //         'descripcion' => 'required',
    //         'descripcion_incluye' => 'required',
    //         'descripcion_importante_reservar' => 'required',
    //         'img_recorrido' => '',
    //         'horarios' => 'sometimes|array'

    //     ]);

    //     if ($request->hasFile('img_recorrido')) {
    //         // Almacenar la imagen en 'storage/app/public/Recorridos'
    //         $rutaImagen = $request->file('img_recorrido')->store('Recorridos', 'public');

    //         // Crear la ruta accesible desde la web
    //         $validacion['img_recorrido'] = $rutaImagen;
    //     }



    //     $recorrido = Recorrido::findOrFail($id);        
    //     HorarioRecorrido::where('id_recorrido', $id)->delete();

    //     $recorrido->update($validacion);

    //     // HorarioRecorrido::where('id_recorrido', $id)->delete();


    //     foreach($request->horarios as $dato){
    //         $validar = Validator::make($dato,[
    //             'horario_inicio' => 'required|date_format:H:i:s',
    //             'id_guia' => 'required|integer',
    //             'fecha' => 'required|date',
    //             'horario_fin' => 'required|date_format:H:i:s'
    //         ]);

    //         if($validar->fails()){
    //             throw ValidationException::withMessages([
    //                 "message" => "Validación incorrecta de horario",
    //                 "errors" => $validar->errors()->all(),
    //             ]);
    //         }

    //         HorarioRecorrido::create([
    //             'horario_inicio' => $dato['horario_inicio'],
    //             'disponible' => 1,
    //             'id_recorrido' => $id,
    //             'id_guia' => $dato['id_guia'],
    //             'fecha' => $dato['fecha'],
    //             'horario_fin' => $dato['horario_fin']
    //         ]);

    //     }

    //     return response()->json(['message' => 'Recorridos actualizados con sus horarios correctamente']);
    // }

    // public function updateDatos($request, $id) {
    //     if (!$request->has('horarios') || !is_array($request->horarios)) {
    //         return response()->json(["error" => "El campo horarios es obligatorio y debe ser un array"], 400);
    //     }

    //     // 🔍 Guardamos los estados de disponibilidad antes de eliminarlos
    //     $horariosAnteriores = HorarioRecorrido::where('id_recorrido', $id)
    //         ->get()
    //         ->mapWithKeys(function ($horario) {
    //             return [
    //                 $horario->id . '|||' . $horario->fecha . '|||' . $horario->horario_inicio . '|||' . $horario->id_guia => $horario->disponible
    //             ];
    //         });

    //         Log::info("Horarios anteriores: ", $horariosAnteriores->toArray());
    //     // 🔥 Eliminamos los horarios actuales
    //     HorarioRecorrido::where('id_recorrido', $id)->delete();

    //     // ✅ Validación de datos
    //     $validacion = $request->validate([
    //         'titulo' => 'required|max:45',
    //         'precio' => 'required|numeric',
    //         'duracion' => 'required|date_format:H:i:s',
    //         'descripcion' => 'required',
    //         'descripcion_incluye' => 'required',
    //         'descripcion_importante_reservar' => 'required',
    //         'img_recorrido' => '',
    //         'horarios' => 'sometimes|array'
    //     ]);

    //     if ($request->hasFile('img_recorrido')) {
    //         // Guardar la imagen en 'storage/app/public/Recorridos'
    //         $rutaImagen = $request->file('img_recorrido')->store('Recorridos', 'public');
    //         $validacion['img_recorrido'] = $rutaImagen;
    //     }

    //     // 📌 Actualizar datos del recorrido
    //     $recorrido = Recorrido::findOrFail($id);
    //     $recorrido->update($validacion);

    //     // 🛠 Insertar los nuevos horarios manteniendo su disponibilidad
    //     foreach ($request->horarios as $dato) {
    //         $validar = Validator::make($dato, [
    //             'horario_inicio' => 'required|date_format:H:i:s',
    //             'id_guia' => 'required|integer',
    //             'fecha' => 'required|date',
    //             'horario_fin' => 'required|date_format:H:i:s'
    //         ]);

    //         if ($validar->fails()) {
    //             throw ValidationException::withMessages([
    //                 "message" => "Validación incorrecta de horario",
    //                 "errors" => $validar->errors()->all(),
    //             ]);
    //         }

    //         $esNuevoHorario = !isset($dato['id']); // Verificar si es un nuevo horario

    //         // Buscar si el horario existía antes y conservar su estado de "disponible"
    //         $clave = $dato['id'] . '|||' . $dato['fecha'] . '|||' . $dato['horario_inicio'] . '|||' . $dato['id_guia'];
    //         $disponible = $esNuevoHorario ? 1 : ($horariosAnteriores[$clave] ?? 1);

    //         Log::info("Horarios anteriores: ". $clave);
    //         HorarioRecorrido::create([
    //             'horario_inicio' => $dato['horario_inicio'],
    //             'disponible' => $disponible, // Se mantiene el último estado antes de eliminar
    //             'id_recorrido' => $id,
    //             'id_guia' => $dato['id_guia'],
    //             'fecha' => $dato['fecha'],
    //             'horario_fin' => $dato['horario_fin']
    //         ]);
    //     }

    //     return response()->json(['message' => 'Recorridos actualizados correctamente con sus horarios.']);
    // }
    public function updateDatos($request, $id)
    {

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'entradas_ilimitadas' => 'required|boolean',
            'descuento_alimentos_souvenirs' => 'required|numeric',
            'acceso_eventos' => 'required|boolean',
            'descuento_tours' => 'required|numeric', 
            'experiencias_animales' => 'required|boolean',
            'estacionamiento_preferencial' => 'required|boolean',
            'detras_camaras' => 'required|boolean',
            'recorrido_vip_gratuito' => 'required|boolean',
            'programas_conservacion' => 'required|boolean',
            'descuento_renta_espacios_eventos' => 'required|numeric',
            'precio_especial_invitados' => 'required|numeric',
            'regalo_bienvenida' => 'nullable|string',
            'charlas_educativas' => 'required|boolean',
            'imagen' => 'nullable|string', // Puede ser Base64
        ]);

        // Si la imagen es Base64, conviértela a archivo y guárdala
        if (!empty($validatedData['imagen']) && str_starts_with($validatedData['imagen'], 'data:image')) {
            $imageData = explode(',', $validatedData['imagen'])[1];
            $imagePath = 'imagenes/recorridos/' . uniqid() . '.png';
            Storage::disk('public')->put($imagePath, base64_decode($imageData));
            $validatedData['imagen'] = $imagePath;
        }
        if ($validatedData['imagen'] == null || $validatedData['imagen'] == "") {
            $validatedData['imagen'] = Membresia::findOrFail($id)->imagen;
        } else {
            $validatedData['imagen'] = Membresia::findOrFail($id)->imagen;
        }

        $recorrido = Recorrido::findOrFail($id);
        $recorrido->fill($validatedData);
        $recorrido->save();


        DB::beginTransaction();



        // ✅ Actualizar el recorrido
        $recorrido = Recorrido::findOrFail($id);
        $recorrido->update($validatedData);

        return response()->json(['message' => 'Recorridos actualizados correctamente con sus horarios.']);
    }




    public function eliminadoLogico($request, $id)
    {
        $request->validate([
            "estado" => "required|boolean"
        ]);

        $recorrido = Recorrido::findOrFail($id);
        $recorrido->estado = $request->input('estado');
        $recorrido->save();


        HorarioRecorrido::where('id_recorrido', $id)->update(['disponible' => $request->input('estado')]);

        return response()->json(['message' => 'Eliminado con exito']);
    }

    public function traerRecorridos($request)
    {

        $recorridos = $request->input('active', '');

        if ($recorridos != null) {

            $rs =  Recorrido::where('estado', $recorridos)->get();

            return $rs;
        }
    }
}
