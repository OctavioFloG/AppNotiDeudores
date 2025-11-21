<?php

//Controladores
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CuentaPorCobrarController;
use App\Http\Controllers\Institucional\LoginController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\InstitutionController as AdminInstitutionController;

//Middlewares
use App\Http\Middleware\RedirectIfAuthenticatedToDashboard;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::apiResource('institutions', InstitutionController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('clients', ClientController::class);
Route::apiResource('cuentas-por-cobrar', CuentaPorCobrarController::class);
Route::apiResource('notificaciones', NotificacionController::class);

// Rutas de autenticación institucional
Route::get('login', function () {
    return redirect()->route('institucional.login');
})->name('login');

Route::get('institucional/login', [LoginController::class, 'showLoginForm'])
    ->middleware(RedirectIfAuthenticatedToDashboard::class)
    ->name('institucional.login');
Route::post('institucional/login', [LoginController::class, 'login']);
Route::get('institucional/dashboard', [LoginController::class, 'dashboard'])
    ->middleware('auth')
    ->name('institucional.dashboard');
Route::get('institucional/logout', [LoginController::class, 'logout'])
    ->name('institucional.logout');

// Rutas para cambiar credenciales institucionales
Route::get('institucional/cambiar-contrasena', [LoginController::class, 'showChangeForm'])
    ->middleware('auth')->name('institucional.cambiar-contrasena');
Route::post('institucional/cambiar-contrasena', [LoginController::class, 'changeCredentials'])
    ->middleware('auth');

// Rutas para admin
Route::get('admin/dashboard', [AdminController::class, 'dashboard'])
    ->middleware('auth')->name('admin.dashboard');

Route::middleware('auth')->group(function () {
    // Instituciones
    Route::get('admin/institutions', [AdminInstitutionController::class, 'index'])->name('admin.institutions.index');
    Route::get('admin/institutions/create', [AdminInstitutionController::class, 'create'])->name('admin.institutions.create');
    Route::post('admin/institutions', [AdminInstitutionController::class, 'store'])->name('admin.institutions.store');
});
