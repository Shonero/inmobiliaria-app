<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProyectoInmobiliarioController;
use App\Http\Controllers\Api\UnidadPropiedadController;
use App\Http\Controllers\Api\ClienteController;

// Rutas públicas para auth
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Rutas protegidas con JWT
Route::middleware('auth:api')->group(function () {

    // Auth protegidas
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Proyectos inmobiliarios
    Route::apiResource('proyectos', ProyectoInmobiliarioController::class);

    // Rutas anidadas para unidades dentro de un proyecto
    Route::prefix('proyectos/{proyecto_inmobiliario_id}/unidades')->group(function () {
        Route::get('/', [UnidadPropiedadController::class, 'index']);
        Route::post('/', [UnidadPropiedadController::class, 'store']);
        Route::get('/{unidadId}', [UnidadPropiedadController::class, 'show']);
        Route::put('/{unidadId}', [UnidadPropiedadController::class, 'update']);
        Route::delete('/{unidadId}', [UnidadPropiedadController::class, 'destroy']);

    });

    // Clientes (por si quieres gestionar clientes desde el frontend)
    Route::apiResource('clientes', ClienteController::class);


 // Validaciones personalizadas
    Route::get('/clientes/validar-rut/{rut}', [ClienteController::class, 'validarRut']);
    Route::get('/unidades/validar-numero/{numero_unidad}/{proyecto_id}', [UnidadPropiedadController::class, 'validarNumeroUnidad']);

    //Route::put('/unidades/{id}', [UnidadController::class, 'update']);


});
