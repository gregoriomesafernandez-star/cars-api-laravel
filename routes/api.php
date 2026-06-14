<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::resource('cars', CarController::class);

/*
    GET       /cars              → index    → listar coches
    POST      /cars              → store    → guardar coche
    GET       /cars/{car}        → show     → ver un coche
    PUT/PATCH /cars/{car}        → update   → actualizar coche
    DELETE    /cars/{car}        → destroy  → eliminar coche
*/