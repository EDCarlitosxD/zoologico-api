<?php
namespace App\Services;

use App\Models\Donacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DonacionService{

    public function guardardatos($request, $id, $email, $nombre, $fecha){
        $datos = $request->all();

        $validar = Validator::make($datos, [
            'monto' => 'required|numeric',
        ]);

        if($validar->fails()){
            throw ValidationException::withMessages([
                "message" => "Validacion incorrecta"
            ]);
        }

        Donacion::create([
            'id_usuario' => $id,
            'monto' => $datos['monto'],
            'email' => $email,
            'fecha' => $fecha
        ]);

        $datosrecibo = [
            'nombre_usuario' => $nombre,
            'monto' => $datos['monto']
        ];

        return $datosrecibo;



    }

    public function donacionesUltimaSemana(){
        $haceUnaSemana = Carbon::now()->subDays(7);

        $donacion = Donacion::select('id','monto')->where('fecha', '>=', $haceUnaSemana)->get();

        $totalDonaciones = $donacion->sum('monto');

        $promedioDonaciones = $donacion->avg('monto');

        $datos = [
            "donacion" => $donacion,
            "total" => $totalDonaciones,
            "promedio" => $promedioDonaciones
        ];

        return $datos;
    }

    public function donacionesUltimoMes(){
        $haceUnMes = Carbon::now()->subMonth();

        $donacion = Donacion::select('id','monto')->where('fecha', '>=', $haceUnMes)->get();

        $totalDonaciones = $donacion->sum('monto');

        $promedioDonaciones = $donacion->avg('monto');

        $datos = [
            "donacion" => $donacion,
            "total" => $totalDonaciones,
            "promedio" => $promedioDonaciones
        ];

        return $datos;
    }

    public function donacionesUltimoYear(){
        $ultimoYear = Carbon::now()->year;

        $donacion = Donacion::select('id','monto')->whereYear('fecha', $ultimoYear)->get();

        $totalDonaciones = $donacion->sum('monto');

        $promedioDonaciones = $donacion->avg('monto');

        $datos = [
            "donacion" => $donacion,
            "total" => $totalDonaciones,
            "promedio" => $promedioDonaciones
        ];

        return $datos;
    }

}

?>
