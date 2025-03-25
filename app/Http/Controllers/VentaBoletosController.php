<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\ReciboElectronico;
use App\Models\venta_boletos;
use App\Services\VentaService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Ventas",
 *     description="APIs para la gestión de ventas de boletos"
 * )
 */
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
    /**
     * @OA\Post(
     *     path="/venta",
     *     summary="Procesar una venta de boletos o recorridos",
     *     tags={"Ventas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"boletos", "recorridos"},
     *             @OA\Property(property="boletos", type="array", @OA\Items(
     *                 @OA\Property(property="id_boleto", type="integer", example=1),
     *                 @OA\Property(property="cantidad", type="integer", example=2)
     *             )),
     *             @OA\Property(property="recorridos", type="array", @OA\Items(
     *                 @OA\Property(property="id_recorrido", type="integer", example=1),
     *                 @OA\Property(property="id_horario_recorrido", type="integer", example=1),
     *                 @OA\Property(property="cantidad", type="integer", example=2)
     *             ))
     *         )
     *     ),
     *     @OA\Response(response=200, description="Venta procesada correctamente"),
     *     @OA\Response(response=500, description="Error al procesar la venta")
     * )
     */
    public function guardar(Request $request)
    {
        $token = Str::uuid();
        $id_usuario = Auth::user()->id;
        $email = Auth::user()->email;
        $nombre = Auth::user()->nombre_usuario;
        $fechaactual = date("Y-m-d");
        $boletos = [];
        $recorridos = [];
        $boletosreturn = [];
        $recorridosreturn = [];


        DB::beginTransaction();
        try {
            if ($request->has('boletos')) {
                $boletos = $this->ventaService->procesarVenta($request, $token, $fechaactual, $email, $id_usuario);
            }

            if ($request->has('recorridos')) {
                $recorridos = $this->ventaService->reservarRecorrido($request, $id_usuario, $token, $fechaactual);
            }


            $total = $this->ventaService->calcularTotal($request);



            //Mail::to($email)->send(new ReciboElectronico($boletos, $total, $fechaactual, $nombre, $email, $recorridos));

            DB::commit();



            foreach ($boletos as $boleto) {
                $boletosreturn[] = [
                    "tipo_boleto" => $boleto['tipoboleto'],
                    "cantidad_boletos" => $boleto['cantidad'],
                    "total_boletos" => $boleto['cantidad'] * $boleto['precio'],
                    "token" => $boleto['token']
                ];
            }

            foreach ($recorridos as $recorrido) {
                $recorridosreturn[] = [
                    "tipo_recorrido" => $recorrido['tiporecorrido'],
                    "cantidad" => $recorrido['cantidad'],
                    "total_recorrido" => $recorrido['cantidad'] * $recorrido['precio'],
                    "token" => $recorrido['token'],
                    'hora_inicio' => $recorrido['hora_inicio'],
                    'hora_fin' => $recorrido['hora_fin'],
                    'fecha' => $recorrido['fecha']
                ];
            }

            return $boletosrecorridos = [
                "boletos" => $boletosreturn,
                "recorridos" => $recorridosreturn,
            ];



            /*
            $resultado = [
                'boletos' => $boletosreturn,
                'recorridos' => $recorridosreturn
            ];

            return response()->json($resultado);*/
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function traerVentasGeneral(){
        $ventas = $this->ventaService->ventasGeneral();

        return response()->json($ventas);
    }

    public function boletosVendidos(){
        $boletosvendidos = $this->ventaService->traerVentasGeneral();

        return response()->json($boletosvendidos);
    }

    public function boletosVendidosSemana(){
        $boletosSemana = $this->ventaService->bvendidosSemana();

        return response()->json($boletosSemana);
    }

    public function boletosVendidosMes(){
        $boletosMes = $this->ventaService->bvendidosMes();

        return response()->json($boletosMes);
    }

    public function boletosVendidosYear(){
        $boletosyear = $this->ventaService->bvendidosYear();

        return response()->json($boletosyear);
    }

}
