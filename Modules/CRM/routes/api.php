<?php

use Illuminate\Support\Facades\Route;
use Modules\CRM\Controllers\Api\CustomerController;

Route::group(['middleware' => ['auth:api'], 'as'=>'crm.', 'prefix' => 'crm'], function () {
    Route::apiResource('customers', CustomerController::class);

    //get customers
    Route::get('get-customers', [CustomerController::class, 'getCustomers']);
    Route::get('get-customer-types', [CustomerController::class, 'getCustomerTypes']);
    Route::get('balance-details/{id}', [CustomerController::class, 'balanceDetails']);

});