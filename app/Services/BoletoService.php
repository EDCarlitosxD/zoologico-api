<?php
namespace App\Services;

use App\Models\Boletos;
use App\Models\VistaUsuarioBoletos;
use App\Models\VistaUsuarioRecorridos;


class BoletoService {

    public function TraerComprasUsuario ($id_usuario){

        $boletos = VistaUsuarioBoletos::select('titulo', 'fecha', 'cantidad', 'precio_total', 'token')
        ->where('id_usuario', $id_usuario )->get()->groupBy( 'token');

        $reservas = VistaUsuarioRecorridos::select('titulo', 'fecha', 'cantidad', 'precio_total', 'token')
        ->where('id_usuario', $id_usuario)->get()->groupBy('token');

        $compra = [
            'boletos' => $boletos,
            'reservas' => $reservas
        ];

        return $compra;
    }

    public function traerBoletosExistentes (){
        $boletosExistentes = Boletos::select('id', 'titulo', 'precio', 'estado')->get();
        return $boletosExistentes;
    }



}



?>
