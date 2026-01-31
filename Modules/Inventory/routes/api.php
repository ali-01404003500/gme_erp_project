<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Controllers\Api\ProductCatalogApiController;

Route::group(['middleware' => ['auth:api'], 'prefix' => 'inv', 'as' => 'inv.'], function () {
    Route::apiResource('product-catalogs', ProductCatalogApiController::class);
    Route::get('get-product-catalogs', [ProductCatalogApiController::class,'getAllProducts']);
    Route::get('product-catalogs-count', [ProductCatalogApiController::class,'countProduct']);
    Route::post('product-catalogs-import', [ProductCatalogApiController::class,'import']);
    Route::get('product-catalogs-download-sample-csv', [ProductCatalogApiController::class,'downloadSampleCSV']);
    Route::put('product-catalogs/{id}/update-mrp', [ProductCatalogApiController::class,'updateCatalogMrp']);
    Route::get('product-catalogs-price-discount', [ProductCatalogApiController::class,'getProductPriceAndDiscount']);
});