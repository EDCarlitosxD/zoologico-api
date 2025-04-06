<?php

namespace App\Services;

use App\Models\HorarioRecorrido;
use App\Models\Insignias;
use App\Models\Recorrido;
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

class InsigniaService
{

    public function crearInsignia($request)
    {

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'cantidad' => 'required|numeric',
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
            $imagePath = 'imagenes/insignias/' . uniqid() . '.png';
            Storage::disk('public')->put($imagePath, base64_decode($imageData));
            $validatedData['imagen'] = $imagePath;
        }

        $insignia = Insignias::create($validatedData);
        return $insignia;
    }

    public function actualizarInsignia($request, $id)
    {

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'cantidad' => 'required|numeric',
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
            $imagePath = 'imagenes/insignias/' . uniqid() . '.png';
            Storage::disk('public')->put($imagePath, base64_decode($imageData));
            $validatedData['imagen'] = $imagePath;
        } else {
            $validatedData['imagen'] = Insignias::findOrFail($id)->imagen;
        }

        $insignia = Insignias::findOrFail($id);
        $insignia->update($validatedData);
        return $insignia;
    }

    public function updateDatos($request, $id)
    {

        $validatedData = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'cantidad' => 'sometimes|numeric',
            'imagen' => 'nullable|string', // Ahora puede recibir Base64
        ]);

        // Si la imagen es Base64, conviértela a archivo y guárdala
        if (!empty($validatedData['imagen']) && str_starts_with($validatedData['imagen'], 'data:image')) {
            $imageData = explode(',', $validatedData['imagen'])[1];
            $imagePath = 'imagenes/recorridos/' . uniqid() . '.png';
            Storage::disk('public')->put($imagePath, base64_decode($imageData));
            $validatedData['imagen'] = $imagePath;
        }
        if ($validatedData['imagen'] == null || $validatedData['imagen'] == "") {
            $validatedData['imagen'] = Insignias::findOrFail($id)->imagen;
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
