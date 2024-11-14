<?php

namespace App\Http\Controllers;

use App\Models\venta_boletos;
use App\Models\Boletos;
use App\Mail\ReciboElectronico;
use App\Service\VentaService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class VentaBoletosController
{

    public function __construct()
    {
 
    }


    public function index()
    {
        //
    }

    public function guardar(Request $request){

        $token = Str::uuid();
        $email = Auth::user()->email;
        $nombre = Auth::user()->name;
        $fechaactual = date("Y-m-d");
        $datos = $request->all();
        $total = 0;


        try {
            foreach($datos as $dato){
                $validar = Validator::make($dato,[
                    'id_usuario' => 'required',
                    'id_boleto' => 'required',
                    'cantidad' => 'required|integer',
                ]);

                if($validar->fails()){
                    throw ValidationException::withMessages([
                        "message" => "Validacion incorrecta"
                    ]);
                }

                venta_boletos::create([
                    'id_usuario' => $dato['id_usuario'],
                    'id_boleto' => $dato['id_boleto'],
                    'fecha' => $fechaactual,
                    'cantidad' => $dato['cantidad'],
                    'token' => $token,
                    'email' => $email
                ]);

                $precio = Boletos::select('precio')->where('id', $dato['id_boleto'])->first();

                $total += $dato['cantidad'] * $precio->precio;

            }
                
                $boletos = $this->arregloboletos($request);

                Mail::to($email)->send(new ReciboElectronico($boletos, $total, $fechaactual, $nombre, $email));

            DB::commit();
          }
        catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
          }

        
    }

    public function arregloboletos(Request $request){
        $boletos = [];
        $datos = $request->all();
        foreach ($datos as $dato){
            $tipoboleto = Boletos::select('titulo')->where('id', $dato['id_boleto'])->first();
            $boletos[] = [
                'tipoboleto' => $tipoboleto->titulo,
                'cantidad' => $dato['cantidad']
            ];
        }
        return $boletos;
    }
    
}
