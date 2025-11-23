<?php

use App\Http\Controllers\Api\InstitutionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\CuentaPorCobrarController;
use App\Http\Controllers\Api\ClientAuthController;
use App\Http\Controllers\Api\DebtNotificationController;
use App\Http\Controllers\Api\NotificacionController;
use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\InstitutionDashboardController;
use Illuminate\Support\Facades\Route;


// ========== RUTAS PUBLICAS ==========

Route::post('auth/login', [AuthController::class, 'login']);

Route::post('client-auth/generate-token/{id_cliente}', [ClientAuthController::class, 'generateAndSendToken']);
Route::post('client-auth/validate-token', [ClientAuthController::class, 'validateToken']);

Route::post('marcar-token-usado/{id_token}', [DebtNotificationController::class, 'marcarTokenUsado']);

// ========== RUTAS PROTEGIDAS (Token de Institucion) ==========

Route::middleware('auth:sanctum')->group(function () {

    // ========== ADMIN ==========
    Route::post('institutions', [InstitutionController::class, 'store']);
    Route::get('institutions', [InstitutionController::class, 'index']);
    Route::get('admin/dashboard', [AdminDashboardController::class, 'dashboard']);


    // ========== AUTH ==========
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/change-password', [AuthController::class, 'changePassword']);
    Route::get('auth/me', [AuthController::class, 'me']);


    // ========== INSTITUCION DASHBOARD ==========
    Route::get('institution/dashboard', [InstitutionDashboardController::class, 'dashboard']);


    // ========== CLIENTES ==========
    Route::post('institution/clientes', [ClientController::class, 'store']);
    Route::get('institution/clientes', [ClientController::class, 'index']);
    Route::get('institution/clientes/{id_cliente}', [ClientController::class, 'show']);
    Route::put('institution/clientes/{id_cliente}', [ClientController::class, 'update']);
    Route::delete('institution/clientes/{id_cliente}', [ClientController::class, 'destroy']);


    // Generar token para cliente (desde panel institucion)
    Route::post('institution/clientes/{id_cliente}/send-token', [ClientAuthController::class, 'generateAndSendToken']);


    // ========== DEUDAS / CUENTAS POR COBRAR ==========
    Route::post('institution/deudas', [CuentaPorCobrarController::class, 'store']);
    Route::get('institution/deudas', [CuentaPorCobrarController::class, 'index']);
    Route::get('institution/deudas/{id}', [CuentaPorCobrarController::class, 'show']);
    Route::put('institution/deudas/{id}', [CuentaPorCobrarController::class, 'update']);
    Route::delete('institution/deudas/{id}', [CuentaPorCobrarController::class, 'destroy']);
    Route::put('institution/deudas/{id}/pagar', [CuentaPorCobrarController::class, 'registrarPago']);


    // ========= NOTIFICACIONES =========
    Route::post('notificaciones/enviar', [NotificacionController::class, 'enviar']);
    Route::get('notificaciones', [NotificacionController::class, 'listar']);
    Route::get('notificaciones/deuda/{id_cuenta}', [NotificacionController::class, 'obtenerPorDeuda']);
    Route::get('notificaciones/institucion/{id_institucion}', [NotificacionController::class, 'obtenerPorInstitucion']);
    Route::patch('notificaciones/{id_notificacion}/leida', [NotificacionController::class, 'marcarComoLeida']);
    Route::delete('notificaciones/{id_notificacion}', [NotificacionController::class, 'eliminar']);


    // ========= ENVIO DE ENLACE DE DEUDA AL CLIENTE ==========
    Route::post('enviar-deudas-link/{id_cliente}', [DebtNotificationController::class, 'enviarDeudaConLink']);
});


// ========== RUTAS PROTEGIDAS (Token de Cliente) ==========

Route::middleware('auth:client')->group(function () {
    Route::get('client/my-debts', [ClientAuthController::class, 'myDebts']);
    Route::get('client/me', [ClientAuthController::class, 'me']);
});
