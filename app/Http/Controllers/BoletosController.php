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
        
        $resultado = $this->boletoService->TraerBoletosUsuario($id_usuario);

        return response()->json($resultado);

    }

    
}
