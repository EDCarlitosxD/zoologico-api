<?php

namespace App\Services;

use App\Models\HorarioRecorrido;
use App\Models\Recorrido;
use App\Models\User;
use App\Models\VistaRecorridosReservadosMes;
use App\Models\VistaRecorridosReservadosSemana;
use App\Models\VistaRecorridosReservadosYear;
use App\Notifications\NuevoRecorridoAgregado;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RecorridoService
{

    public function crearRecorrido($request)
    {

        $usuarios = User::all(); 

        $datos = $request->all();

        $validar = Validator::make($datos, [
            'titulo' => 'required|max:45',
            'precio' => 'required|numeric',
            'duracion' => 'required',
            'descripcion' => 'required',
            'descripcion_incluye' => 'required',
            'descripcion_importante_reservar' => 'required',
            'img_recorrido' => 'nullable'
        ]);

        if ($validar->fails()) {
            throw ValidationException::withMessages(
                $validar->errors()->toArray(),
            );
        }

        if ($request->hasFile('img_recorrido')) {
            $datos['img_recorrido'] = $request->file('img_recorrido')->store('Recorridos', 'public');
            // ESTA LINEA SIRVE PARA OBTENER EL NOMBRE DE LA IMAGEN DE LA RUTA
        }

        $recorrido = Recorrido::create([
            'titulo' => $datos['titulo'],
            'precio' => $datos['precio'],
            'duracion' => $datos['duracion'],
            'descripcion' => $datos['descripcion'],
            'descripcion_incluye' => $datos['descripcion_incluye'],
            'descripcion_importante_reservar' => $datos['descripcion_importante_reservar'],
            'img_recorrido' => $datos['img_recorrido']
        ]);

        //! AGREGAR DATOS DE LOS HORARIOS
        // Before the foreach loop, make sure $datos['horarios'] is an array
        if (isset($datos['horarios'])) {
            // If horarios is a JSON string, decode it
            if (is_string($datos['horarios'])) {
                $datos['horarios'] = json_decode($datos['horarios'], true);
            }

            // Check if it's now an array or object that can be iterated
            if (is_array($datos['horarios']) || is_object($datos['horarios'])) {
                foreach ($datos['horarios'] as $dato) {
                    // Your existing validation and creation code
                    $validar = Validator::make($dato, [
                        'horario_inicio' => 'required|date_format:H:i:s',
                        'id_guia' => 'required|integer',
                        'fecha' => 'required|date',
                        'horario_fin' => 'required|date_format:H:i:s'
                    ]);

                    if ($validar->fails()) {
                        throw ValidationException::withMessages($validar->errors()->toArray());
                    }

                    HorarioRecorrido::create([
                        'horario_inicio' => $dato['horario_inicio'],
                        'disponible' => 1,
                        'id_recorrido' => $recorrido->id,
                        'id_guia' => $dato['id_guia'],
                        'fecha' => $dato['fecha'],
                        'horario_fin' => $dato['horario_fin']
                    ]);
                }
            } else {
                // Handle the case where horarios is not iterable
                return response()->json(['error' => 'El formato de horarios es inválido'], 422);
            }
        }


        return response()->json(['message' => 'recorrido y horarios agregados correctamente']);
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
        if (!$request->has('horarios') || !is_array($request->horarios)) {
            return response()->json(["error" => "El campo horarios es obligatorio y debe ser un array"], 400);
        }

        // ✅ Obtener los horarios actuales antes de modificar la BD
        $horariosAnteriores = HorarioRecorrido::where('id_recorrido', $id)->get()->mapWithKeys(
            function ($horario) {
                return [$horario->id . '|||' . $horario->fecha . '|||' . $horario->horario_inicio . '|||' . $horario->id_guia => $horario->disponible];
            }
        )->toArray(); // 🔥 Convertir a array para evitar problemas con Log

        $validatedData = $request->validate([
            'titulo' => 'sometimes|string|max:255',
            'precio' => 'sometimes|numeric',
            'duracion' => 'sometimes|string',
            'img_recorrido' => 'nullable|string', // Ahora puede recibir Base64
            'descripcion_incluye' => 'sometimes|string',
            'descripcion_importante_reservar' => 'sometimes|string',
            'descripcion' => 'sometimes|string',
            'horarios' => 'sometimes|array'
        ]);

        // Si la imagen es Base64, conviértela a archivo y guárdala
        if (!empty($validatedData['img_recorrido']) && str_starts_with($validatedData['img_recorrido'], 'data:image')) {
            $imageData = explode(',', $validatedData['img_recorrido'])[1];
            $imagePath = 'imagenes/recorridos/' . uniqid() . '.png';
            Storage::disk('public')->put($imagePath, base64_decode($imageData));
            $validatedData['img_recorrido'] = $imagePath;
        } else if ($validatedData['img_recorrido'] == null || $validatedData['img_recorrido'] == "") {
            $validatedData['img_recorrido'] = Recorrido::findOrFail($id)->img_recorrido;
        }

        $recorrido = Recorrido::findOrFail($id);
        $recorrido->fill($validatedData);
        $recorrido->save();


        DB::beginTransaction();



        // ✅ Actualizar el recorrido
        $recorrido = Recorrido::findOrFail($id);
        $recorrido->update($validatedData);

        // ✅ Procesar cada horario (ACTUALIZAR si existe, CREAR si es nuevo)
        foreach ($request->horarios as $dato) {
            $validar = Validator::make($dato, [
                'horario_inicio' => 'required|date_format:H:i:s',
                'id_guia' => 'required|integer',
                'fecha' => 'required|date',
                'horario_fin' => 'required|date_format:H:i:s',
                'disponible' => 'required|boolean'
            ]);

            if ($validar->fails()) {
                throw ValidationException::withMessages([
                    "message" => "Validación incorrecta de horario",
                    "errors" => $validar->errors()->all(),
                ]);
            }

            // ✅ Si el horario tiene ID, se actualiza su estado "disponible"
            if (isset($dato['id'])) {
                HorarioRecorrido::where('id', $dato['id'])
                    ->update(['disponible' => $dato['disponible']]);
            } else {
                // ✅ Si es un nuevo horario, se inserta con el estado correcto
                $clave = $dato['fecha'] . '|||' . $dato['horario_inicio'] . '|||' . $dato['id_guia'];
                $disponible = $horariosAnteriores[$clave] ?? 1;

                HorarioRecorrido::create([
                    'horario_inicio' => $dato['horario_inicio'],
                    'disponible' => $disponible,
                    'id_recorrido' => $id,
                    'id_guia' => $dato['id_guia'],
                    'fecha' => $dato['fecha'],
                    'horario_fin' => $dato['horario_fin']
                ]);
            }
        }

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

    public function rreservadosSemana()
    {
        $recorridosSemana = VistaRecorridosReservadosSemana::select('id', 'titulo', 'cantidad')->get()->groupBy('id');

        $cantidad = VistaRecorridosReservadosSemana::sum('cantidad');

        $datos = [
            "VentaSemana" => $recorridosSemana,
            "cantidad_total" => $cantidad
        ];

        return $datos;
    }

    public function rreservadosMes()
    {
        $recorridosMes = VistaRecorridosReservadosMes::select('id', 'titulo', 'cantidad')->get()->groupBy('id');

        $cantidad = VistaRecorridosReservadosMes::sum('cantidad');

        $datos = [
            "VentaMes" => $recorridosMes,
            "cantidad_total" => $cantidad
        ];

        return $datos;
    }

    public function rreservadosYear()
    {
        $recorridosYear = VistaRecorridosReservadosYear::select('id', 'titulo', 'cantidad')->get()->groupBy('id');

        $cantidad = VistaRecorridosReservadosYear::sum('cantidad');

        $datos = [
            "VentaYear" => $recorridosYear,
            "cantidad_total" => $cantidad
        ];

        return $datos;
    }
}
