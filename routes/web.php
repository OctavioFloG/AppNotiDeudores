<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\InstitutionDashboardController;
use App\Http\Controllers\Web\InstitutionController;

// ========= RUTAS PÚBLICAS =========
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('login', [LoginController::class, 'showLogin'])->name('login');

// ========= RUTAS PROTEGIDAS =========
Route::middleware([\App\Http\Middleware\AuthToken::class])->group(function () {

    // ========= ADMIN =========
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/institutions', [InstitutionController::class, 'index'])->name('admin.institutions.index');
    Route::get('admin/institutions/create', [InstitutionController::class, 'create'])->name('admin.institutions.create');

    // ========= INSTITUCIÓN =========
    Route::get('institution/dashboard', [InstitutionDashboardController::class, 'index'])->name('institution.dashboard');

    Route::get('institution/clientes', [InstitutionDashboardController::class, 'clientes'])->name('institution.clientes.index');
    Route::get('institution/clientes/crear', [InstitutionDashboardController::class, 'crearCliente'])->name('institution.clientes.crear');
    
    Route::get('institution/deudas', [InstitutionDashboardController::class, 'deudas'])->name('institution.deudas.index');
    Route::get('institution/deudas/crear', [InstitutionDashboardController::class, 'crearDeuda'])->name('institution.deudas.crear');
});
