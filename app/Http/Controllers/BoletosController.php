<?php

namespace App\Http\Controllers;

use App\Models\boletos;
use App\Http\Controllers\Controller;
use App\Models\venta_boletos;
use App\Services\BoletoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;

/**
 * @OA\Tag(
 *     name="Boletos",
 *     description="APIs para la gestión de boletos"
 * )
 */
class BoletosController
{
    protected $boletoService;
    public function __construct(BoletoService $boletoService)
    {
        $this->boletoService = $boletoService;
    }

     public function boletosUsuario()
     {
         $id_usuario = Auth::user()->id;

         $resultado = $this->boletoService->TraerComprasUsuario($id_usuario);

         return response()->json($resultado);
     }








    /**
     * @OA\Get(
     *     path="/admin/boletos",
     *     summary="Obtener boletos existentes",
     *     tags={"Boletos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista de boletos obtenida correctamente")
     * )
     */
    public function boletosExistentes()
    {
        $boletosExistentes = $this->boletoService->traerBoletosExistentes();

        return response()->json($boletosExistentes);
    }


    /**
     * @OA\Get(
     *     path="/boletos",
     *     summary="Obtener todos los boletos",
     *     tags={"Boletos"},
     *     @OA\Response(response=200, description="Lista de boletos obtenida correctamente")
     * )
     */
    public function all(Request $request)
    {
        $query = boletos::query()->where('estado', 1);



        return $query->get()->map(function ($boleto) {
            $boleto->imagen = asset('storage/' . $boleto->imagen);
            return $boleto;
        });
    }


    public function getById($id)
    {
        $boleto = boletos::findOrFail($id);
        $boleto->imagen = asset('storage/' . $boleto->imagen);

        return $boleto;
    }

    /**
     * @OA\Post(
     *     path="/boletos",
     *     summary="Crear un nuevo boleto",
     *     tags={"Boletos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"titulo", "descripcion", "precio", "imagen", "descripcion_card", "advertencias"},
     *             @OA\Property(property="titulo", type="string", example="Boleto VIP"),
     *             @OA\Property(property="descripcion", type="string", example="Acceso exclusivo"),
     *             @OA\Property(property="precio", type="number", format="float", example=99.99),
     *             @OA\Property(property="imagen", type="string", format="binary"),
     *             @OA\Property(property="descripcion_card", type="string", example="Acceso VIP"),
     *             @OA\Property(property="advertencias", type="string", example="No incluye alimentos"),
     *         )
     *     ),
     *     @OA\Response(response=201, description="Boleto creado con éxito")
     * )
     */
    public function save(Request $request)
    {
        $request->validate([
            'titulo' => 'required|max:80',
            "descripcion" => 'required|max:45',
            "precio" => 'required|numeric',
            "imagen" => "required",
            'descripcion_card' => 'required',
            "advertencias" => 'required',
        ]);

        $datos = $request->all();
        //        $datos['imagen']= $request->file('imagen')->store('Boletos', 'public');

        $boleto = new boletos($datos);
        $boleto->save();
        $boleto->imagen = asset('storage/' . $boleto->imagen);
        return response(["boleto" => $boleto], Response::HTTP_CREATED);
    }

    

    // /**
    //  * @OA\Put(
    //  *     path="/boletos/eliminar/{id}",
    //  *     summary="Eliminar un boleto (eliminación lógica)",
    //  *     tags={"Boletos"},
    //  *     security={{"bearerAuth":{}}},
    //  *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
    //  *     @OA\RequestBody(
    //  *         required=true,
    //  *         @OA\JsonContent(
    //  *             required={"estado"},
    //  *             @OA\Property(property="estado", type="boolean", example=false)
    //  *         )
    //  *     ),
    //  *     @OA\Response(response=200, description="Boleto eliminado correctamente")
    //  * )
    //  */
     public function delete(Request $request, $id)
     {
         $request->validate([
             'estado' => 'required|boolean',
         ]);

         $boleto = boletos::findOrFail($id);

         $boleto->estado = $request->input('estado');
         $boleto->save();

         return response(['message' => 'Boleto estado actualizado con exito'], Response::HTTP_ACCEPTED);
     }


    /**
     * @OA\Get(
     *     path="/venta/boletos",
     *     summary="Obtener boletos vendidos",
     *     tags={"Boletos"},
     *     @OA\Response(response=200, description="Lista de boletos vendidos obtenida correctamente")
     * )
     */
    public function boletosVendidos()
    {
        $ventas = DB::table('venta_boletos as venta')
            ->join('boletos', 'venta.id_boleto', '=', 'boletos.id')
            ->select(
                'venta.id as id',
                'boletos.titulo as titulo',
                'venta.precio_total as precio_total',
                'venta.cantidad as cantidad'
            )
            ->paginate(20);

        return response()->json($ventas);
    }

    public function actualizar(Request $request, $id)
    {
        $boleto = boletos::findOrFail($id);
        $boleto->update($request->all());
        return response(['message' => 'Boleto actualizado con exito'], Response::HTTP_ACCEPTED);
    }
}
