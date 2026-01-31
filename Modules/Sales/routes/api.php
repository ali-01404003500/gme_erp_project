<?php

use Illuminate\Support\Facades\Route;
use Modules\Sales\Controllers\Api\CourierApiController;
use Modules\Sales\Controllers\Api\QuotationController;
use Modules\Sales\Controllers\Api\SalesOrderController;
use Modules\Sales\Controllers\Api\SalesRequisitionController;

Route::group(['middleware' => ['auth:api'],'prefix' => 'sales', 'as' => 'sales.'], function () {
    Route::apiResource('sales-orders', SalesOrderController::class);
    Route::get('sales-orders-all', [SalesOrderController::class, 'getAllSalesOrder'])->name('sales-orders.getAllSalesOrder');
    Route::apiResource('sales-requisitions', SalesRequisitionController::class);
    Route::apiResource('quotations', QuotationController::class);
    Route::get('quotations-customer-types', [QuotationController::class, 'getCustomerTypes'])->name('quotations.customer-types');


     Route::resource('couriers', CourierApiController::class);
     Route::get('all-couriers', [CourierApiController::class, 'getAllCouriers'])->name('couriers.get-all-couriers');
});