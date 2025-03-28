<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatBotController;

/*
|---------------------------------------------------------------------------
| API Routes
|---------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas de tu API para tu aplicación.
| Estas rutas son cargadas por el RouteServiceProvider y todas ellas
| serán asignadas al grupo de middleware "api". ¡Haz algo grandioso!
|
*/

// Ruta protegida por autenticación API
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); // Usamos sanctum para API en Laravel 10

