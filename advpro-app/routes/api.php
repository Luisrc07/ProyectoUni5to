<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importar controladores API
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\ContratoController;
use App\Http\Controllers\Api\ProyectoController;
use App\Http\Controllers\Api\EquipoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas para la API. Se cargan por el RouteServiceProvider
| dentro del grupo de middleware `api`, asegurando que sean tratadas como API REST.
|--------------------------------------------------------------------------
*/

// Ruta de ejemplo para autenticación de usuario (si la necesitas)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// --- Rutas API Resource ---
// Estas rutas generarán los endpoints RESTful estándar (index, store, show, update, destroy)
// para cada recurso. No incluirán 'create' ni 'edit' por defecto.

Route::apiResource('clientes', ClienteController::class);
Route::apiResource('contratos', ContratoController::class);
Route::apiResource('proyectos', ProyectoController::class);
Route::apiResource('equipos', EquipoController::class);

// 🛡️ Opcional: Agrupar rutas bajo autenticación Sanctum si toda la API la requiere
/*
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('clientes', ClienteController::class);
    Route::apiResource('contratos', ContratoController::class);
    Route::apiResource('proyectos', ProyectoController::class);
    Route::apiResource('equipos', EquipoController::class);
});
*/