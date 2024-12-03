<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Services\RecorridoService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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




    }
}
