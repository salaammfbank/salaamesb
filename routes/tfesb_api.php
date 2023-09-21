<?php

use Illuminate\Support\Facades\Route;
use Noorfarooqy\Tfesb\Controllers\ApiCustomerController;


Route::group(['prefix' => '/api/v1/tfesb', 'as' => 'tfesb.'], function () {


    Route::group(['prefix' => '/customer', 'as' => 'customer.'], function () {
        Route::post('/', [ApiCustomerController::class, 'GetCustomerDetails'])->name('details');
    });
});
