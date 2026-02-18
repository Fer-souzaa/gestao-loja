<?php

use App\Packages\Auth\Controllers\AuthController;
use App\Packages\Color\Controllers\ColorController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->group(function () {

    /**
     * Autenticação
     */
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });

    /**
     * Cores
     */
    Route::prefix('colors')->name('colors.')->group(function () {
        Route::get('', [ColorController::class, 'index'])->name('index');
        Route::post('', [ColorController::class, 'store'])->name('store');
        Route::put('{id}', [ColorController::class, 'update'])->name('update');
        Route::delete('{id}', [ColorController::class, 'destroy'])->name('destroy');
    });
});
