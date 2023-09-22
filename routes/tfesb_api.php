<?php

use Illuminate\Support\Facades\Route;
use Noorfarooqy\EnterpriseServiceBus\Controllers\ApiCustomerController;


Route::group(['prefix' => '/api/v1/esb', 'as' => 'esb.'], function () {


    Route::group(['prefix' => '/customer', 'as' => 'customer.'], function () {
        Route::post('/', [ApiCustomerController::class, 'GetCustomerDetails'])->name('details');
    });
});
