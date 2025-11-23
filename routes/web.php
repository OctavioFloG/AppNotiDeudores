<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\InstitutionDashboardController;

// ========= RUTAS PÚBLICAS =========
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('login', [LoginController::class, 'showLogin'])
    ->name('login');;

// ========= RUTAS PROTEGIDAS =========
Route::middleware([\App\Http\Middleware\AuthToken::class])->group(function () {

    // ========= ADMIN =========
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // ========= INSTITUCIÓN =========
    Route::prefix('institution')->group(function () {
        // Dashboard
        Route::get('dashboard', [InstitutionDashboardController::class, 'index'])->name('institution.dashboard');

        // Clientes
        Route::get('clientes', [InstitutionDashboardController::class, 'clientes'])->name('institution.clientes.index');
        Route::get('clientes/crear', [InstitutionDashboardController::class, 'crearCliente'])->name('institution.clientes.create');
        Route::get('clientes/{id}', [InstitutionDashboardController::class, 'Cliente'])->name('institution.clientes.show');

        // Deudas
        Route::get('deudas', [InstitutionDashboardController::class, 'deudas'])->name('institution.deudas.index');
        Route::get('deudas/crear', [InstitutionDashboardController::class, 'crearDeuda'])->name('institution.deudas.create');
    });
});
