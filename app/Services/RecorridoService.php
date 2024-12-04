<?php 
namespace App\Services;

use App\Models\HorarioRecorrido;
use App\Models\Recorrido;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RecorridoService{

    public function crearRecorrido($request){

        $datos = $request->all();
 
        $validar = Validator::make($datos, [
            'titulo' => 'required|max:45',
            'precio' => 'required|numeric',
            'duracion' => 'required|date_format:H:i:s',
            'descripcion' => 'required',
            'descripcion_incluye' => 'required',
            'descripcion_importante_reservar' => 'required',
            'img_recorrido' => 'required|max:255'
        ]);

        if($validar->fails()){
            throw ValidationException::withMessages([
                "message" => "Validación incorrecta",
                "errors" => $validar->errors()->all(),
            ]);
        }

        $datos['img_recorrido']= $request->file('img_recorrido')->store('Recorridos', 'public');

        $recorrido = Recorrido::create([
            'titulo' => $datos['titulo'],
            'precio' => $datos['precio'],
            'duracion' => $datos['duracion'],
            'descripcion' => $datos['descripcion'],
            'descripcion_incluye' => $datos['descripcion_incluye'],
            'descripcion_importante_reservar' => $datos['descripcion_importante_reservar'],
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

    public function updateDatos($request, $id){
 
        $validacion = $request->validate([
            'titulo' => 'required|max:45',
            'precio' => 'required|numeric',
            'duracion' => 'required|date_format:H:i:s',
            'descripcion' => 'required',
            'descripcion_incluye' => 'required',
            'descripcion_importante_reservar' => 'required',
            'img_recorrido' => 'required'
        ]);

        $recorrido=Recorrido::findOrFail($id);

        $recorrido->update($validacion);

        HorarioRecorrido::where('id_recorrido', $id)->delete();
        

        foreach($request->horarios as $dato){
            $validar = Validator::make($dato,[
                'horario_inicio' => 'required|date_format:H:i:s',
                'id_guia' => 'required|integer',
                'fecha' => 'required|date',
                'horario_fin' => 'required|date_format:H:i:s'
            ]);

            if($validar->fails()){
                throw ValidationException::withMessages([
                    "message" => "Validación incorrecta de horario",
                    "errors" => $validar->errors()->all(),
                ]);
            }

            HorarioRecorrido::create([
                'horario_inicio' => $dato['horario_inicio'],
                'disponible' => 1,
                'id_recorrido' => $id,
                'id_guia' => $dato['id_guia'],
                'fecha' => $dato['fecha'],
                'horario_fin' => $dato['horario_fin']
            ]);

        }

        return response()->json(['message' => 'Recorridos actualizados con sus horarios correctamente']);
    }

    public function eliminadoLogico($request, $id){
        $request->validate([
            "estado" => "required|boolean"
        ]);

        $recorrido = Recorrido::findOrFail($id);
        $recorrido->estado = $request->input('estado');
        $recorrido->save();


        HorarioRecorrido::where('id_recorrido', $id)->update(['disponible' => $request->input('estado')]);

        return response()->json(['message' => 'Eliminado con exito']);
    }

    


}






?>