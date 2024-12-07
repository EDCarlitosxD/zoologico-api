<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ReciboElectronicoDonacion;
use App\Services\DonacionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DonacionController
{
    protected $donacionService;

    public function __construct(DonacionService $donacionService)
    {
       $this->donacionService = $donacionService;
    }

    public function guardar(Request $request){
        $idusuario = Auth::user()->id;
        $email = Auth::user()->email;
        $nombre = Auth::user()->nombre_usuario;
        $fecha= date("Y-m-d");


        $datos = $this->donacionService->guardardatos($request, $idusuario, $email, $nombre, $fecha);

        Mail::to($email)->send(new ReciboElectronicoDonacion($datos, $email, $nombre, $fecha));

        return response()->json(['message' => 'Venta procesada correctamente'], 200);

    }

    public function donacionesSemana(){
        $donacion = $this->donacionService->donacionesUltimaSemana();

        return response()->json($donacion);
    }

    public function donacionesMes(){
        $donacion = $this->donacionService->donacionesUltimoMes();

        return response()->json($donacion);
    }

    public function donacionesYear(){
        $donacion = $this->donacionService->donacionesUltimoYear();

        return response()->json($donacion);
    }
}
