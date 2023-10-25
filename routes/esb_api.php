<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Noorfarooqy\SalaamEsb\Http\Controllers\AccountController;
use Noorfarooqy\SalaamEsb\Http\Controllers\ApiCustomerController;
use Noorfarooqy\SalaamEsb\Http\Controllers\AuthController;

Route::group(['prefix' => '/api/v1/esb', 'as' => 'api.esb'], function () {

    Route::middleware(['guest'])->group(function () {
        Route::post('/login', [AuthController::class, 'apiLogin'])->name('api.login');
        if (Features::enabled(Features::registration())) {
            Route::post('/register', [AuthController::class, 'apiRegister'])->name('api.register');
        }
    });


    Route::middleware(['auth:sanctum'])->group(function () {
        Route::group(['prefix' => '/accounts', 'as' => 'api.account.'], function () {
            Route::post('/profile/update', [AccountController::class, 'UpdateProfile'])->name('update')->middleware('can:read_accounts', 'can:update_accounts');
            Route::post('/profile/reset', [AccountController::class, 'ResetProfilePassword'])->name('reset')->middleware('can:read_accounts', 'can:update_accounts');
        });
    });

    Route::middleware(['auth:sanctum', 'system_verif:0'])->group(function () {


        Route::group(['prefix' => 'bank', 'as' => 'bank.'], function () {
            Route::group(['prefix' => '/customer', 'as' => 'customer.'], function () {
                Route::post('/', [ApiCustomerController::class, 'GetCustomerDetails'])->name('details');
                Route::post('/onboard', [ApiCustomerController::class, 'OnboardCustomer'])->name('details');
            });
        });
    });
});
