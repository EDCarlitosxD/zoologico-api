<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tarjeta;
use App\Services\TarjetaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarjetaController
{
    protected $tarjetaService;

    public function __construct(TarjetaService $tarjetaService)
    {
        $this->tarjetaService = $tarjetaService;
    }


    public function guardar (Request $request){

        $idusuario = Auth::user()->id;

        $tarjeta = $this->tarjetaService->procesarTarjeta($request, $idusuario);

        return $tarjeta;

    }

    public function eliminar($id){


        $eliminartarjeta = $this->tarjetaService->eliminarTarjeta($id);

        return $eliminartarjeta;

    }

    public function getTarjetas($id){
        return Tarjeta::all();
    }

}
