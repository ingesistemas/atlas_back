<?php

use App\Http\Controllers\Api\CiudadController;
use App\Http\Controllers\Api\DptoController;
use App\Http\Controllers\Api\EmpresaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UsuarioController;

Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'consultar']);
    Route::post('/', [RoleController::class, 'crear']);
    Route::get('/{id}', [RoleController::class, 'show']);
    Route::put('/{id}', [RoleController::class, 'editar']);
    Route::delete('/{id}', [RoleController::class, 'estado']);
});

Route::prefix('empresas')->group(function () {
    Route::get('/', [EmpresaController::class, 'consultar']);
    Route::post('/', [EmpresaController::class, 'crear']);
    Route::get('/{id}', [EmpresaController::class, 'show']);
    Route::put('/{id}', [EmpresaController::class, 'editar']);
    Route::delete('/{id}', [EmpresaController::class, 'estado']);
});

Route::prefix('dptos')->group(function () {
    Route::get('/', [DptoController::class, 'consultar']);
});

Route::prefix('ciudades')->group(function () {
    Route::get('/', [CiudadController::class, 'consultar']);
});

Route::prefix('usuarios')->group(function () {
    Route::get('/', [UsuarioController::class, 'consultar']);
    Route::post('/ingresar', [UsuarioController::class, 'ingresar']);
   
});