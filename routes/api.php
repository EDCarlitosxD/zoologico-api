<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoletosController;
use App\Http\Controllers\DonacionController;
use App\Http\Controllers\HorarioRecorridoController;
use App\Http\Controllers\RecorridoController;
use App\Http\Controllers\TarjetaController;
use App\Http\Controllers\VentaBoletosController;
use App\Http\Controllers\VerificationEmailController;
use App\Models\Boletos;
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

//Animales
Route::get('/animales/card/', [AnimalController::class, 'ImgAnimal']);

Route::get('/animales/{slug}', [AnimalController::class, 'animalslug']);

Route::post('/animales', [AnimalController::class, 'guardar']);
Route::get('/animales', [AnimalController::class, 'getAll']);

Route::put('/animales/eliminar/{id}', [AnimalController::class, 'actualizarEstado']);

Route::put('/animales/actualizar/{id}', [AnimalController::class,'actualizar']);


// Horarrio
Route::get('horrario/recorrido/{id}', [HorarioRecorridoController::class,'getHorariosGroupByRecorridos']);




//Tarjeta
Route::get('tarjeta/{id}', [TarjetaController::class,'getTarjetas']);
Route::post('/tarjeta', [TarjetaController::class, 'guardar'])->middleware('auth:sanctum');
Route::delete('/tarjeta/eliminar/{id}', [TarjetaController::class, 'eliminar'])->middleware('auth:sanctum');

//Donacion

Route::post('/donaciones/guardar', [DonacionController::class, 'guardar'])->middleware('auth:sanctum');


//Recorridos

Route::get('recorridos', [RecorridoController::class,'getAllRecorridosActive']);
Route::get('admin/recorridos', [RecorridoController::class,'getAllRecorridosActive']);
Route::post('/recorridos/guardar', [RecorridoController::class, 'guardar'])->middleware('auth:sanctum');
Route::put('/recorridos/actualizar/{id}', [RecorridoController::class, 'actualizar'])->middleware('auth:sanctum');
Route::put('/recorridos/eliminar/{id}', [RecorridoController::class, 'eliminar'])->middleware('auth:sanctum') ;
// Route::get('/recorridos', [RecorridoController::class, 'seleccionarRecorrido'])->middleware('auth:sanctum');


Route::get('/boletos/obtener', [BoletosController::class, 'boletosUsuario'])->middleware('auth:sanctum');
Route::get('venta/boletos', [BoletosController::class, 'boletosVendidos']);

//Traer boletos existentes (Dashboard boletos)
Route::get('admin/boletos', [BoletosController::class, 'boletosExistentes']);

//Traer ventas generales
// Route::get('/ventas', [VentaBoletosController::class, 'traerVentasGeneral'])->middleware('auth:sanctum');
Route::post('/venta', [VentaBoletosController::class, 'guardar'])->middleware('auth:sanctum');
Route::get('/venta/usuario', [BoletosController::class, 'boletosUsuario'])->middleware('auth:sanctum');

