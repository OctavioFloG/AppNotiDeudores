<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CuentaPorCobrarController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::apiResource('institutions', InstitutionController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('clients', ClientController::class);
Route::apiResource('cuentas-por-cobrar', CuentaPorCobrarController::class);
Route::apiResource('notificaciones', NotificacionController::class);
