<?php

namespace App\Http\Controllers;

Use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\ReciboElectronico;
use App\Services\VentaService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class VentaBoletosController
{

    protected $ventaService;

    public function __construct(VentaService $ventaService)
    {
        $this->ventaService = $ventaService;
    }


    public function index()
    {
        //
    }

    public function guardar(Request $request)
    {
        $token = Str::uuid();
        $id_usuario = Auth::user()->id;
        $email = Auth::user()->email;
        $nombre = Auth::user()->nombre_usuario;
        $fechaactual = date("Y-m-d");

        DB::beginTransaction();
        try {
            $boletos = $this->ventaService->procesarVenta($request, $token, $fechaactual, $email, $id_usuario);
            $total = $this->ventaService->calcularTotal($request);


            Mail::to($email)->send(new ReciboElectronico($boletos, $total, $fechaactual, $nombre, $email));

            DB::commit();
            return response()->json(['message' => 'Venta procesada correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function ventasmayor (){
        
    }
    
}
