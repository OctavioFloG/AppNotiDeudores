<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\InstitutionController;
use Illuminate\Support\Facades\Route;

// Rutas públicas (sin autenticación)
Route::post('institutions', [InstitutionController::class, 'store']);
Route::post('auth/login', [AuthController::class, 'login']);

// Rutas protegidas (requieren token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/change-password', [AuthController::class, 'changePassword']);
    Route::get('auth/me', [AuthController::class, 'me']);
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('institutions', [InstitutionController::class, 'store']);