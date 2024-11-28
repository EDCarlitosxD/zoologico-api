<?php 
namespace App\Services;

use App\Models\HorarioRecorrido;
use App\Models\Recorrido;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RecorridoService{

    public function crearRecorrido($request){

        $datos = $request->all();
 
        $validar = Validator::make($datos, [
            'titulo' => 'required|max:45',
            'precio' => 'required|numeric',
            'descripcion' => 'required',
            'duracion' => 'required|date_format:H:i:s',
            'cantidad_personas' => 'required|integer',
            'precio_persona_extra' => 'required|numeric',
            'img_recorrido' => 'required|max:255'
        ]);

        if($validar->fails()){
            throw ValidationException::withMessages([
                "message" => "Validación incorrecta",
                "errors" => $validar->errors()->all(),
            ]);
        }

        $recorrido = Recorrido::create([
            'titulo' => $datos['titulo'],
            'precio' => $datos['precio'],
            'descripcion' => $datos['descripcion'],
            'duracion' => $datos['duracion'],
            'cantidad_personas' => $datos['cantidad_personas'],
            'precio_persona_extra' => $datos['precio_persona_extra'],
            'img_recorrido' => $datos['img_recorrido']
        ]);


        foreach($datos['horarios'] as $dato){
            $validar = Validator::make($dato,[
                'horario_inicio' => 'required|date_format:H:i:s',
                'id_guia' => 'required|integer',
                'fecha' => 'required|date',
                'horario_fin' => 'required|date_format:H:i:s'
            ]);

            if($validar->fails()){
                throw ValidationException::withMessages([
                    "message" => "Validación incorrecta",
                    "errors" => $validar->errors()->all(),
                ]);
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

        return response()->json(['message' => 'recorrido y horarios agregados correctamente']);
        
    }



}






?>