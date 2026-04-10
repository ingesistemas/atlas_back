<?php

use App\Http\Controllers\Api\BarrioController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\CiudadController;
use App\Http\Controllers\Api\DptoController;
use App\Http\Controllers\Api\LocalidadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------
*/

Route::prefix('usuarios')->group(function () {
    Route::post('/ingresar', [UsuarioController::class, 'ingresar']);
});

Route::get('/dptos', [DptoController::class, 'consultar']);
Route::get('/ciudades', [CiudadController::class, 'consultar']);

Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'consultar']);
    Route::post('/', [RoleController::class, 'crear']);
    Route::get('/{id}', [RoleController::class, 'show']);
    Route::put('/{id}', [RoleController::class, 'editar']);
    Route::delete('/{id}', [RoleController::class, 'estado']);
});


/*
|--------------------------------------------------
| RUTAS PROTEGIDAS (JWT + BD EMPRESA)
|--------------------------------------------------
*/

Route::middleware('empresa.jwt')->group(function () {

    Route::get('/usuarios', [UsuarioController::class, 'consultar']);
    Route::get('/test-db', [UsuarioController::class, 'testDb']);

    Route::prefix('usuarios')->group(function () {
        Route::post('/', [UsuarioController::class, 'crear']);
        Route::get('/{id}', [UsuarioController::class, 'show']);
        Route::put('/{id}', [UsuarioController::class, 'editar']);
        Route::delete('/{id}', [UsuarioController::class, 'estado']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'consultar']);
        Route::post('/', [RoleController::class, 'crear']);
        Route::get('/{id}', [RoleController::class, 'show']);
        Route::put('/{id}', [RoleController::class, 'editar']);
        Route::delete('/{id}', [RoleController::class, 'estado']);
    });

    Route::prefix('localidades')->group(function () {
        Route::get('/', [LocalidadController::class, 'consultar']);
        Route::post('/', [LocalidadController::class, 'crear']);
        Route::put('/{localidad}', [LocalidadController::class, 'editar']);
        Route::delete('/{id}', [LocalidadController::class, 'estado']);
    });

    Route::prefix('barrios')->group(function () {
        Route::get('/', [BarrioController::class, 'consultar']);
        Route::post('/', [BarrioController::class, 'crear']);
        Route::put('/{localidad}', [BarrioController::class, 'editar']);
        Route::delete('/{id}', [BarrioController::class, 'estado']);
    });

    Route::prefix('empresas')->group(function () {
        Route::get('/', [EmpresaController::class, 'consultar']);
       
    });
});
