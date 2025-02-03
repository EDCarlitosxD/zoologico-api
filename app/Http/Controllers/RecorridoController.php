<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Services\RecorridoService;
use Exception;
use App\Models\Recorrido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class RecorridoController
{
    protected $recorridoService;
    public function __construct(RecorridoService $recorridoService)
    {
        $this->recorridoService = $recorridoService;
    }

    public function guardar(Request $request){

        DB::beginTransaction();

        try{
            $registro = $this->recorridoService->crearRecorrido($request);
            DB::commit();
            return $registro;
        }
        catch(Exception $e){
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function actualizar(Request $request, $id){

        DB::beginTransaction();

        try{
            $update = $this->recorridoService->updateDatos($request, $id);
            DB::commit();
            return $update;
        }
        catch (Exception $e){
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()],500);
        }

    }

    public function eliminar(Request $request, $id){

        $eliminado = $this->recorridoService->eliminadoLogico($request, $id);

        return $eliminado;

    }

    public function seleccionarRecorrido (Request $request){
        $select = $this->recorridoService->traerRecorridos($request);

        return response()->json($select);

    }

    public function recorridosReservadosSemana (){
        $recorridos = $this->recorridoService->rreservadosSemana();

        return response()->json($recorridos);
    }

    public function recorridosReservadosMes (){
        $recorridos = $this->recorridoService->rreservadosMes();

        return response()->json($recorridos);
    }

    public function recorridosReservadosYear (){
        $recorridos = $this->recorridoService->rreservadosYear();

        return response()->json($recorridos);
    }


    //
    public function getAllRecorridosActive(){
        $recorridos = Recorrido::where('estado',1)->get();

        foreach ($recorridos as $recorrido) {
            $recorrido->img_recorrido = asset('storage') . '/' . ($recorrido->img_recorrido);
        }
        return $recorridos;
    }

    public function getAllRecorridos(){
        return response(Recorrido::all(), Response::HTTP_OK);
    }


    public function getById($id){
         $reco = Recorrido::findOrFail($id);
        $reco->img_recorrido = asset('storage'). '/' . ($reco->img_recorrido);
        return $reco;
        }

}
