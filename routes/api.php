<?php

use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Ramsey\Uuid\Type\Integer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

Route::get('/user', function (Request $request) {
    return $request->user()->load('role');
})->middleware('auth:sanctum');

Route::get('/user/{id}', function($id){

    $user = User::find($id);

    if(!$user){
        throw  new NotFoundHttpException("No se encontro el usuario");
    }

    return $user;
});


Route::post("/login", [AuthController::class,'login']);
Route::post("/register", [AuthController::class,'register']);
Route::post("/logout", [AuthController::class,'logout'])->middleware("auth:sanctum");
