<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoletosController;
use App\Http\Controllers\DonacionController;
use App\Http\Controllers\FormularioContactoController;
use App\Http\Controllers\GuiaController;
use App\Http\Controllers\HorarioRecorridoController;
use App\Http\Controllers\RecorridoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\TarjetaController;
use App\Http\Controllers\VentaBoletosController;
use App\Http\Controllers\VerificationEmailController;
use App\Models\Boletos;
use App\Models\HorarioRecorrido;
use App\Models\Reserva;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Ramsey\Uuid\Type\Integer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/user/{id}', function($id){

    $user = User::find($id);

    if(!$user){
        throw  new NotFoundHttpException("No se encontro el usuario");
    }

    return $user;
});




Route::get('email/verify/{id}/{hash}', [VerificationEmailController::class, 'verify'])
    ->name('verification.verify');
//Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');

Route::post("/login", [AuthController::class,'login'])->name('login');
Route::post("/register", [AuthController::class,'register']);
Route::post("/logout", [AuthController::class,'logout'])->middleware("auth:sanctum");

//Editar datos de la cuenta del usuario
Route::put('/cuenta', [AuthController::class, 'EditarDatos'])->middleware("auth:sanctum");

//Animales
Route::get('/animales/card/', [AnimalController::class, 'imgAnimal']);

Route::get('/animales/{slug}', [AnimalController::class, 'animalslug']);

Route::post('/animales', [AnimalController::class, 'guardar']);
Route::get('/animales', [AnimalController::class, 'getAll']);

Route::put('/animales/eliminar/{id}', [AnimalController::class, 'actualizarEstado']);

Route::put('/animales/actualizar/{id}', [AnimalController::class,'actualizar']);


// Horarrio
Route::get('horrario/recorrido/{id}', [HorarioRecorridoController::class,'getHorariosGroupByRecorridos']);

Route::get('horarrios/{id}', [HorarioRecorridoController::class, 'getById']);


//Tarjeta
Route::get('tarjeta/{id}', [TarjetaController::class,'getTarjetas']);
Route::post('/tarjeta', [TarjetaController::class, 'guardar'])->middleware('auth:sanctum');
Route::delete('/tarjeta/eliminar/{id}', [TarjetaController::class, 'eliminar'])->middleware('auth:sanctum');

//Donacion

Route::post('/donaciones/guardar', [DonacionController::class, 'guardar'])->middleware('auth:sanctum');


//Recorridos

Route::get('recorridos', [RecorridoController::class,'getAllRecorridosActive']);
Route::get('recorridos/{id}', [RecorridoController::class,'getById']);
Route::get('admin/recorridos', [RecorridoController::class,'getAllRecorridos']);
Route::post('/recorridos/guardar', [RecorridoController::class, 'guardar'])->middleware('auth:sanctum');
Route::put('/recorridos/actualizar/{id}', [RecorridoController::class, 'actualizar']);
Route::put('/recorridos/eliminar/{id}', [RecorridoController::class, 'eliminar'])->middleware('auth:sanctum') ;
// Route::get('/recorridos', [RecorridoController::class, 'seleccionarRecorrido'])->middleware('auth:sanctum');
Route::post('boletos',[BoletosController::class, 'save']);

Route::get('/boletos/obtener', [BoletosController::class, 'boletosUsuario'])->middleware('auth:sanctum');
Route::get('venta/boletos', [BoletosController::class, 'boletosVendidos']);
Route::get('boletos',[BoletosController::class, 'all']);
//Traer boletos existentes (Dashboard boletos)
Route::get('admin/boletos', [BoletosController::class, 'boletosExistentes']);

//Traer ventas generales
// Route::get('/ventas', [VentaBoletosController::class, 'traerVentasGeneral'])->middleware('auth:sanctum');
Route::post('/venta', [VentaBoletosController::class, 'guardar'])->middleware('auth:sanctum');
Route::get('/venta/usuario', [BoletosController::class, 'boletosUsuario'])->middleware('auth:sanctum');
Route::get('reservas', [ReservaController::class, 'getReservas']);




Route::get('guias',[GuiaController::class,'getAll']);
Route::put('guias/{id}',[GuiaController::class,'actualizar']);
Route::get('guias/{id}',[GuiaController::class,'getById']);
Route::put('guias/eliminar/{id}', [GuiaController::class, 'actualizarEstado']);
Route::get('/ventas', [VentaBoletosController::class, 'traerVentasGeneral'])->middleware('auth:sanctum');

//Traer venta de boletos (dashboard boletos grafica)
Route::get('/boletosvendidos', [VentaBoletosController::class, 'boletosVendidos'])->middleware('auth:sanctum');

//Traer venta de boletos por semana (dashboard reportes grafica)
Route::get('/boletossemana', [VentaBoletosController::class,'boletosVendidosSemana'])->middleware('auth:sanctum');

//Traer venta de boletos por mes (dashboard reportes grafica)
Route::get('/boletosmes', [VentaBoletosController::class,'boletosVendidosMes'])->middleware('auth:sanctum');

//Traer venta de boletos por año (dashboard reportes grafica)
Route::get('/boletosyear', [VentaBoletosController::class,'boletosVendidosYear'])->middleware('auth:sanctum');

//Traer reserva de recorridos por semana (dashboard reportes grafica)
Route::get('/recorridosemana', [RecorridoController::class,'recorridosReservadosSemana'])->middleware('auth:sanctum');

//Traer reserva de recorridos por mes (dashboard reportes grafica)
Route::get('/recorridosmes', [RecorridoController::class,'recorridosReservadosMes'])->middleware('auth:sanctum');

//Traer reserva de recorridos por year (dashboard reportes grafica)
Route::get('/recorridosyear', [RecorridoController::class,'recorridosReservadosYear'])->middleware('auth:sanctum');

//Traer donaciones por semana (dashboard reportes grafica)
Route::get('/donacionsemana', [DonacionController::class,'donacionesSemana'])->middleware('auth:sanctum');

//Traer donaciones por mes (dashboard reportes grafica)
Route::get('/donacionmes', [DonacionController::class,'donacionesMes'])->middleware('auth:sanctum');

//Traer donaciones por year en curso (dashboard reportes grafica)
Route::get('/donacionyear', [DonacionController::class,'donacionesYear'])->middleware('auth:sanctum');

//Mensaje enviado por el usuario
Route::post('/mensajeusuario', [FormularioContactoController::class, 'mensajeusuario']);
