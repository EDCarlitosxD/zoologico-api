<?php

namespace App\Http\Controllers;

use App\Models\boletos;
use App\Http\Controllers\Controller;
use App\Services\BoletoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BoletosController
{
    protected $boletoService;
    public function __construct(BoletoService $boletoService)
    {
        $this->boletoService = $boletoService;
    }

    public function boletosUsuario (){
        $id_usuario = Auth::user()->id;
        
        $resultado = $this->boletoService->TraerComprasUsuario($id_usuario);

        return response()->json($resultado);

    }

    public function boletosExistentes(){
        $boletosExistentes = $this->boletoService->traerBoletosExistentes();

        return response()->json($boletosExistentes);
    }

    public function boletosvendidosfecha(Request $request){
        
        $request->validate([
            'fecha' => 'required|date'
        ]);

        $fecha = $request->input('fecha');

        try {
            
            DB::statement('SET @totalVentas = 0');

            
            DB::statement('CALL TotalBoletos(?, @totalVentas)', [$fecha]);

            
            $result = DB::select('SELECT @totalVentas AS totalVentas');

            
            $totalVentas = $result[0]->totalVentas ?? 0;

            
            return response()->json([
                'totalVentas' => $totalVentas,
            ]);

        } catch (\Exception $e) {
            
            return response()->json([
                'error' => 'Hubo un problema al consultar el total de ventas.',
                'mensaje' => $e->getMessage(),
            ], 500);
        }
    }

    
}
