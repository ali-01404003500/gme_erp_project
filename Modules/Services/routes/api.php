<?php

use Illuminate\Support\Facades\Route;
use Modules\Services\Controllers\Api\ProblemTypeController;
use Modules\Services\Controllers\Api\ServiceController;
use Modules\Services\Controllers\Api\ServiceMyTaskController;

Route::group(['middleware' => ['auth:api'], 'prefix' => 'services', 'as' => 'services.'], function () {
    Route::get('service-get-serial', [ServiceController::class, 'getSerialIds']);
    Route::get('get-invoice', [ServiceController::class, 'getInvoiceBySerial']);
    Route::post('store-dongle', [ServiceController::class, 'storeDongle']);

    Route::get('problem-types-search', [ProblemTypeController::class, 'search']);
    Route::post('problem-types-store', [ProblemTypeController::class, 'store']);

    Route::apiResource('services', ServiceController::class);
    Route::apiResource('service-my-task', ServiceMyTaskController::class);
    Route::get('get-service-for-my-task', [ServiceMyTaskController::class, 'serviceMyTask']);

});