<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="API de Zoológico",
 *     version="1.0.0",
 *     description="API para gestionar recursos del zoológico, incluyendo insignias y otros elementos",
 *     @OA\Contact(
 *         email="contacto@zoologico.com",
 *         name="Equipo de Soporte"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     description="Servidor Local",
 *     url=L5_SWAGGER_CONST_HOST
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}