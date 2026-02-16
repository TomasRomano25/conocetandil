<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LugarController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InicioSectionController;
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
    Route::get('inicio', [InicioSectionController::class, 'index'])->name('inicio.index');
    Route::put('inicio/{inicioSection}', [InicioSectionController::class, 'update'])->name('inicio.update');
    Route::post('inicio/reorder', [InicioSectionController::class, 'reorder'])->name('inicio.reorder');
});
