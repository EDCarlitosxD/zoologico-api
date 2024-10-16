<?php
use App\Http\Controllers\AnimalController;
use Illuminate\Support\Facades\Route;

//GET obtener 
//POST guardar
//PUT actualizar
//DELETE eliminar

Route::get("/",[AnimalController::class, 'holaMundo']);

Route::get("/tipo_animales", [AnimalController::class, 'getAllTipoAnimales']);

Route::get("/usuarios", [AnimalController::class, 'ObtenerUsuarios'] );

Route::get("/imganimal", [AnimalController::class, 'ObtenerImgAnimal']);
