<?php
namespace App\Services;

use App\Models\Boletos;
use App\Models\Recorrido;
use App\Models\VistaUsuarioBoletos;
use App\Models\VistaUsuarioRecorridos;
use Illuminate\Support\Facades\Storage;

class BoletoService {

    public function TraerComprasUsuario ($id_usuario){

        $boletos = VistaUsuarioBoletos::select('titulo', 'fecha', 'cantidad', 'precio_total', 'token')
        ->where('id_usuario', $id_usuario )->get()->groupBy( 'token');

        $reservas = VistaUsuarioRecorridos::select('titulo', 'fecha', 'cantidad', 'precio_total', 'token')
        ->where('id_usuario', $id_usuario)->get()->groupBy('token');

        $compra = [
            'boletos' => $boletos,
            'reservas' => $reservas
        ];

        return $compra;
    }

    public function traerBoletosExistentes (){
        $boletosExistentes = Boletos::select('id', 'titulo', 'precio', 'estado')->get();
        return $boletosExistentes;
    }

    public function updateBoleto($request, $id){
        $validatedData = $request->validate([
            'titulo' => 'sometimes|string|max:255',
            'descripcion_card' => 'sometimes',
            'descripcion' => 'sometimes|string',
            'advertencias' => 'nullable|string', // Ahora puede recibir Base64
            'precio' => 'sometimes|numeric',
            'imagen' => 'nullable|string',
        ]);
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen'); // Obtiene el archivo
            $imageData = base64_encode(file_get_contents($file->getRealPath())); // Convierte a Base64
            $mimeType = $file->getClientMimeType(); // Obtiene el tipo de archivo (image/png, image/jpeg, etc.)
            $validatedData['imagen'] = "data:$mimeType;base64,$imageData"; // Guardar en Base64 en la BD
        }
        if (!empty($validatedData['imagen']) && str_starts_with($validatedData['imagen'], 'data:image')) {
            $imageData = explode(',', $validatedData['imagen'])[1];
            $imagePath = 'boletos/' . uniqid() . '.png';
            Storage::disk('public')->put($imagePath, base64_decode($imageData));
            $validatedData['imagen'] = $imagePath;
        } else {
            $validatedData['imagen'] = Boletos::findOrFail($id)->imagen;
        }

        $boleto = Boletos::findOrFail($id);
        $boleto->fill($validatedData);
        $boleto->update();

        return $boleto;
    }

    public function createBoleto($request){
        $validatedData = $request->validate([
            'titulo' => 'required|max:80',
            "descripcion" => 'required|string',
            "precio" => 'required|numeric',
            "imagen" => "required",
            'descripcion_card' => 'sometimes|string',
            "advertencias" => 'required',
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

        $boleto = Boletos::create($validatedData);
        return $boleto;
    }



}



?>
