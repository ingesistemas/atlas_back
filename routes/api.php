<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoleController;

Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'consultar']);
    Route::post('/', [RoleController::class, 'crear']);
    Route::get('/{id}', [RoleController::class, 'show']);
    Route::put('/{id}', [RoleController::class, 'editar']);
    Route::delete('/{id}', [RoleController::class, 'estado']);
});