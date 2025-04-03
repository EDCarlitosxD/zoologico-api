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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RecorridoService{

    public function crearRecorrido($request){

        $usuarios = User::all(); 

        $datos = $request->all();

        $validar = Validator::make($datos, [
            'titulo' => 'required|max:45',
            'precio' => 'required|numeric',
            'duracion' => 'required|date_format:H:i:s',
            'descripcion' => 'required',
            'descripcion_incluye' => 'required',
            'descripcion_importante_reservar' => 'required',
            'img_recorrido' => 'nullable|file|mimes:jpg,jpeg,png'
        ]);

        if($validar->fails()){
            throw ValidationException::withMessages($validar->errors()->toArray(),
            );
        }

        if ($request->hasFile('img_recorrido')) {
            $datos['img_recorrido'] = $request->file('img_recorrido')->store('Recorridos', 'public');
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

        foreach($usuarios as $usuario){
            $usuario->notify(new NuevoRecorridoAgregado($recorrido, $usuario));
        }



        if (isset($datos['horarios'])) {

            foreach($datos['horarios'] as $dato){
                $validar = Validator::make($dato,[
                    'horario_inicio' => 'required|date_format:H:i:s',
                    'id_guia' => 'required|integer',
                    'fecha' => 'required|date',
                    'horario_fin' => 'required|date_format:H:i:s'
                ]);

                if($validar->fails()){
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
            'img_recorrido' => 'required|file|mimes:jpg,jpeg,png|max:255'
        ]);

        if ($request->hasFile('img_recorrido')) {
            $rutaImagen = $request->file('img_recorrido')->store('Recorridos','public');
            $validacion['img_recorrido'] = $rutaImagen;
        }

        $recorrido = Recorrido::findOrFail($id);
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

    public function traerRecorridos ($request){

        $recorridos = $request->input('active', '');

        if ($recorridos != null) {

           $rs =  Recorrido::where('estado', $recorridos)->get();

           return $rs;
        }

    }

    public function rreservadosSemana(){
        $recorridosSemana = VistaRecorridosReservadosSemana::select('id', 'titulo', 'cantidad')->get()->groupBy('id');

        $cantidad = VistaRecorridosReservadosSemana::sum('cantidad');

        $datos = [
            "VentaSemana" => $recorridosSemana,
            "cantidad_total" => $cantidad
        ];

        return $datos;

    }

    public function rreservadosMes(){
        $recorridosMes = VistaRecorridosReservadosMes::select('id', 'titulo', 'cantidad')->get()->groupBy('id');

        $cantidad = VistaRecorridosReservadosMes::sum('cantidad');

        $datos = [
            "VentaMes" => $recorridosMes,
            "cantidad_total" => $cantidad
        ];

        return $datos;
    }

    public function rreservadosYear(){
        $recorridosYear = VistaRecorridosReservadosYear::select('id', 'titulo', 'cantidad')->get()->groupBy('id');

        $cantidad = VistaRecorridosReservadosYear::sum('cantidad');

        $datos = [
            "VentaYear" => $recorridosYear,
            "cantidad_total" => $cantidad
        ];

        return $datos;
    }
}

?>
