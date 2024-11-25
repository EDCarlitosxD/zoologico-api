<?php

namespace Tests\Feature;

use App\Models\Boletos;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BoletoControllerTest extends TestCase
{

    use RefreshDatabase;


    // public function test_obtenerTodosLosBoletos(){

    //     // Preparar el escenario
    //     $boletos = Boletos::factory()->count(1)->create();

    //     $boletos->map(function($boleto) {
    //         $boleto->imagen = asset('storage/' . $boleto->imagen);
    //         return $boleto;
    //     });

    //     // Realizar las acciones necesarias

    //     $response = $this->getJson('/api/boletos');

    //     // Comprobar el estado final

    //     $response->assertOk(200);
    //     $response->assertJson($boletos->toArray());
    // }

}
