<?php

use Illuminate\Support\Facades\Route;
use Noorfarooqy\SalaamEsb\Controllers\ApiCustomerController;

Route::group(['prefix' => '/api/v1/salaamesb', 'as' => 'salaamesb.'], function () {


    Route::group(['prefix' => '/customer', 'as' => 'customer.'], function () {
        Route::post('/', [ApiCustomerController::class, 'GetCustomerDetails'])->name('details');
    });
});
