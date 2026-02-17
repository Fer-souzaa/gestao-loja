<?php

use App\Packages\Auth\Controllers\AuthController;
use App\Packages\Color\Controllers\ColorController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->group(function () {

    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });

    Route::prefix('colors')->name('colors.')->group(function () {
        Route::get('', [ColorController::class, 'index'])->name('index');
    });
});
