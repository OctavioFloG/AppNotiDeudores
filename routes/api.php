<?php

use App\Http\Controllers\Api\InstitutionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\CuentaPorCobrarController;
use App\Http\Controllers\Api\ClientAuthController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('auth/login', [AuthController::class, 'login']);

// Rutas para admin
Route::middleware('auth:sanctum')->group(function () {
    // Instituciones
    Route::post('institutions', [InstitutionController::class, 'store']);
    Route::get('institutions', [InstitutionController::class, 'index']);
});

// Rutas para clientes (públicas - no necesitan token)
Route::post('client-auth/generate-token/{id_cliente}', [ClientAuthController::class, 'generateAndSendToken']);
Route::post('client-auth/validate-token', [ClientAuthController::class, 'validateToken']);

// Rutas protegidas institución (requieren token de institución)
Route::middleware('auth:sanctum')->group(function () {
    // Auth institución
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

    // Generar token para cliente (desde panel institución)
    Route::post('clients/{id_cliente}/send-token', [ClientAuthController::class, 'generateAndSendToken']);
});

// Rutas protegidas cliente (requieren token de cliente)
Route::middleware('auth:client')->group(function () {
    Route::get('client/my-debts', [ClientAuthController::class, 'myDebts']);
});
