<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post("/login", [AuthController::class,'login']);
Route::post("/register", [AuthController::class,'register']);
Route::post("/logout", [AuthController::class,'logout'])->middleware("auth:sanctum");

//Animales
Route::get('/animales/imagen/{dato}', [AnimalController::class, 'ImgAnimal']);

Route::get('/animales/{slug}', [AnimalController::class, 'animalslug']);

Route::post('/animales', [AnimalController::class, 'guardar']);

Route::put('/animales/eliminar/{id}', [AnimalController::class, 'actualizarEstado']);

Route::put('/animales/actualizar/{id}', [AnimalController::class,'actualizar']);
