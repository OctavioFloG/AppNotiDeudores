<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\InstitucionalDashboardController;
use App\Http\Controllers\Web\InstitutionController;

// ========= RUTAS PÚBLICAS =========
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('login', [LoginController::class, 'showLogin'])->name('login');

// ========= RUTAS PROTEGIDAS =========
Route::middleware([\App\Http\Middleware\AuthToken::class])->group(function () {
    // Admin routes
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/institutions', [InstitutionController::class, 'index'])->name('admin.institutions.index');
    Route::get('admin/institutions/create', [InstitutionController::class, 'create'])->name('admin.institutions.create');
    
    // Institución routes
    Route::get('institucional/dashboard', [InstitucionalDashboardController::class, 'index'])->name('institucional.dashboard');
    Route::get('institucional/clientes', [InstitucionalDashboardController::class, 'clientes'])->name('institucional.clientes');
    Route::get('institucional/clientes/crear', [InstitucionalDashboardController::class, 'crearCliente'])->name('institucional.clientes.crear');
});
