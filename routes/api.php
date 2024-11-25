<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonacionController;
use App\Http\Controllers\TarjetaController;
use App\Http\Controllers\VentaBoletosController;
use App\Http\Controllers\VerificationEmailController;
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

Route::put('/animales/eliminar/{id}', [AnimalController::class, 'actualizarEstado']);

Route::put('/animales/actualizar/{id}', [AnimalController::class,'actualizar']);

//Compra de boletos

Route::post('/boletos/guardardatos', [VentaBoletosController::class, 'guardar'])->middleware('auth:sanctum');

//Tarjeta

Route::post('/tarjeta', [TarjetaController::class, 'guardar'])->middleware('auth:sanctum');

Route::delete('/tarjeta/eliminar/{id}', [TarjetaController::class, 'eliminar'])->middleware('auth:sanctum');

//Donacion

Route:: post('/donaciones/guardar', [DonacionController::class, 'guardar'])->middleware('auth:sanctum');


