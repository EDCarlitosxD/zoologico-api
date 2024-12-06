<?php 
namespace App\Services;

use App\Models\VistaUsuarioBoletos;
use App\Models\VistaUsuarioRecorridos;

class BoletoService {

    public function TraerBoletosUsuario ($id_usuario){

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


}



?>