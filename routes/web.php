<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LugarController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InicioSectionController;
use App\Http\Controllers\Admin\NavItemController;
use App\Http\Controllers\Admin\ConfigurationController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [PageController::class, 'inicio'])->name('inicio');
Route::get('/lugares', [PageController::class, 'lugares'])->name('lugares');
Route::get('/lugares/{lugar}', [PageController::class, 'lugar'])->name('lugar.show');
Route::get('/guias', [PageController::class, 'guias'])->name('guias');
Route::get('/contacto', [PageController::class, 'contacto'])->name('contacto');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('lugares', LugarController::class)->parameters(['lugares' => 'lugar']);
    Route::resource('usuarios', UserController::class)->except(['show']);

    // Inicio sections (specific routes before parameterised)
    Route::get('inicio', [InicioSectionController::class, 'index'])->name('inicio.index');
    Route::post('inicio/reorder', [InicioSectionController::class, 'reorder'])->name('inicio.reorder');
    Route::post('inicio/hero-image', [InicioSectionController::class, 'updateHeroImage'])->name('inicio.hero-image');
    Route::delete('inicio/hero-image', [InicioSectionController::class, 'deleteHeroImage'])->name('inicio.hero-image.delete');
    Route::put('inicio/{inicioSection}', [InicioSectionController::class, 'update'])->name('inicio.update');

    // Nav menu management
    Route::get('nav', [NavItemController::class, 'index'])->name('nav.index');
    Route::post('nav/reorder', [NavItemController::class, 'reorder'])->name('nav.reorder');
    Route::put('nav/{navItem}', [NavItemController::class, 'update'])->name('nav.update');

    // Configuraciones
    Route::get('configuraciones', [ConfigurationController::class, 'index'])->name('configuraciones.index');
    Route::post('configuraciones/backup', [ConfigurationController::class, 'updateBackup'])->name('configuraciones.backup.update');
    Route::post('configuraciones/backup/run', [ConfigurationController::class, 'runBackup'])->name('configuraciones.backup.run');
    Route::get('configuraciones/backup/download', [ConfigurationController::class, 'downloadBackup'])->name('configuraciones.backup.download');
});
