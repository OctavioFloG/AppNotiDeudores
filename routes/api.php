<?php

use App\Http\Controllers\Api\InstitutionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\CuentaPorCobrarController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('institutions', [InstitutionController::class, 'store']);
Route::post('auth/login', [AuthController::class, 'login']);

// Rutas protegidas (requieren token)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/change-password', [AuthController::class, 'changePassword']);
    Route::get('auth/me', [AuthController::class, 'me']);

    // Clients
    Route::post('clients', [ClientController::class, 'store']);
    Route::get('clients', [ClientController::class, 'index']);
    Route::get('clients/{id}', [ClientController::class, 'show']);
    Route::put('clients/{id}', [ClientController::class, 'update']);
    Route::delete('clients/{id}', [ClientController::class, 'destroy']);
    
    // Cuentas por Cobrar
    Route::post('cuentas-por-cobrar', [CuentaPorCobrarController::class, 'store']);
    Route::get('cuentas-por-cobrar', [CuentaPorCobrarController::class, 'index']);
    Route::get('cuentas-por-cobrar/{id}', [CuentaPorCobrarController::class, 'show']);
    Route::put('cuentas-por-cobrar/{id}', [CuentaPorCobrarController::class, 'update']);
    Route::post('cuentas-por-cobrar/{id}/pago', [CuentaPorCobrarController::class, 'registrarPago']);
    Route::delete('cuentas-por-cobrar/{id}', [CuentaPorCobrarController::class, 'destroy']);
});
