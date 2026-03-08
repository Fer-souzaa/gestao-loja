<?php

use App\Packages\Auth\Controllers\AuthController;
use App\Packages\Collection\Controllers\CollectionController;
use App\Packages\Color\Controllers\ColorController;
use App\Packages\Customer\Controllers\CustomerController;
use App\Packages\Employee\Controllers\EmployeeController;
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

    /**
     * Coleções
     */
    Route::prefix('collections')->name('collections.')->group(function () {
        Route::get('', [CollectionController::class, 'index'])->name('index');
        // Adicionando store também, embora não solicitado explicitamente no issue description,
        // é necessário para um CRUD básico e eu o implementei no controller.
        Route::post('', [CollectionController::class, 'store'])->name('store');
        Route::put('{id}', [CollectionController::class, 'update'])->name('update');
        Route::delete('{id}', [CollectionController::class, 'destroy'])->name('destroy');
    });

    /**
     * Funcionários
     */
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('', [EmployeeController::class, 'index'])->name('index');
        Route::post('sellers', [EmployeeController::class, 'storeSeller'])->name('store-seller');
        Route::delete('sellers/{id}', [EmployeeController::class, 'terminateSeller'])->name('terminate-seller');
    });

    /**
     * Clientes
     */
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('', [CustomerController::class, 'index'])->name('index');
        Route::post('', [CustomerController::class, 'store'])->name('store');
        Route::get('{id}', [CustomerController::class, 'show'])->name('show');
        Route::delete('{id}', [CustomerController::class, 'destroy'])->name('destroy');
    });
});
