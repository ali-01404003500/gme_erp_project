<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Controllers\BranchController;
use Modules\Inventory\Controllers\CenterWiseStockReportController;
use Modules\Inventory\Controllers\IssueProductController;
use Modules\Inventory\Controllers\OfferController;
use Modules\Inventory\Controllers\Product\ProductController;
use Modules\Inventory\Controllers\Product\Settings\BrandController;
use Modules\Inventory\Controllers\Product\Settings\ProductTypeController;
use Modules\Inventory\Controllers\ProductCatalogController;
use Modules\Inventory\Controllers\ProductPriceListController;
use Modules\Inventory\Controllers\ProductStockReportController;
use Modules\Inventory\Controllers\ProductTransferController;
use Modules\Inventory\Controllers\ProductTransferReceiveController;
use Modules\Inventory\Controllers\ProductTransferReportController;
use Modules\Inventory\Controllers\ProductTransferRequestController;
use Modules\Inventory\Controllers\Settings\ApproverController;
use Modules\Inventory\Controllers\Settings\TagController;
use Modules\Inventory\Controllers\Settings\UnitController;
use Modules\Inventory\Controllers\StockBalanceReportController;
use Modules\Inventory\Controllers\StockController;
use Modules\Sales\Controllers\SalesOrderController;

Route::group(['middleware'=>'auth', 'prefix' => 'inv', 'as' => 'inv.'],function () {
        /* Product Catalog */
       Route::resource('product-catalogs', ProductCatalogController::class);
       /* Product */
       Route::resource('products', ProductController::class);
       Route::get('products-count', [ProductCatalogController::class, 'countProduct'])->name('products.count');
       //updateCatalogMrp
         Route::post('update-catalog-mrp/{id}', [ProductCatalogController::class, 'updateCatalogMrp'])->name('products.update-catalog-mrp');


        Route::get('product-catalogs-autocomplete-product-name', [ProductCatalogController::class, 'productNameAutocomplete']) ->name('product-catalogs-autocomplete.product-name');
        Route::get('product-catalogs-autocomplete-product-model', [ProductCatalogController::class, 'productModelAutocomplete']) ->name('product-catalogs-autocomplete.product-model');


       /* Issue Product */
       Route::resource('issue-products', IssueProductController::class);

       /* Product Transfers requests*/
       Route::resource('product-transfer-requests', ProductTransferRequestController::class);

       /* Product Transfers */
       Route::resource('product-transfers', ProductTransferController::class);

       /* Product Transfer Receives */
       Route::resource('product-transfer-receives', ProductTransferReceiveController::class);
       Route::get('product-transfer-receives/approve/{id}', [ProductTransferReceiveController::class, 'approve'])->name('product-transfer-receives.approve');

       /*  Offers */
       Route::resource('offers', OfferController::class);


       /* Product Type */
       Route::resource('product-types', ProductTypeController::class);
       Route::get('product-types/{id}/product-catalogs/', [ProductTypeController::class, 'getProducts'])->name('product-types.product-catalogs');


       /* branchs */
   
       Route::resource('brands', BrandController::class);
       Route::get('brands/{id}/product-catalogs/', [BrandController::class, 'getProductCatalogs'])->name('brands.product-catalogs');

       Route::get('stock/stock-in-hand', [StockController::class, 'stockInHand'])->name('stocks.stocks-in-hand');
       Route::get('stock/product-ledger/{id}', [StockController::class, 'productLedger'])->name('stocks.product-ledger');
       Route::get('stock/product-available-in-branch', [StockController::class, 'productAvailableInBranch'])->name('stocks.product-available-in-branch');

       Route::get('stock/stock-in-hand/export',[StockController::class, 'export']);
       Route::get('products-price-list', [ProductPriceListController::class, 'index'])->name('products.price-list');
       Route::get('products-price-list/export', [ProductPriceListController::class, 'export'])->name('products.price-list.export');

       Route::post('product-catalogs-import', [ProductCatalogController::class, 'import'])->name('product-catalogs.import');
       Route::post('products-import', [ProductCatalogController::class, 'importProducts'])->name('products.import');
       Route::get('product-catalogs-download-sample-csv', [ProductCatalogController::class, 'downloadSampleCSV'])->name('product-catalogs.download.sample.csv');

       Route::group(['prefix' => 'settings', 'as'=> 'settings.'], function () {
           /* units */
           Route::resource('approvers', ApproverController::class)->except(['show', 'edit', 'create']);
           Route::resource('units', UnitController::class)->except(['show', 'edit', 'create']);
           Route::resource('tags', TagController::class)->except(['show', 'edit', 'create']);
       });

       Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('product-stock', [ProductStockReportController::class, 'index'])
            ->name('product-stock');
        Route::get('product-transfer', [ProductTransferReportController::class, 'index'])->name('product-transfer');
        Route::get('catalogue-report', [ProductCatalogController::class, 'catalogueReport'])
        ->name('catalogue-report');
        Route::get('catalogue-file/{productId}/{fileIndex}', [ProductCatalogController::class, 'viewCatalogueFile'])->name('view-catalogue-file');
        Route::get('stock-balance', [StockBalanceReportController::class, 'index'])->name('stock-balance');

        Route::get('center-stock', [CenterWiseStockReportController::class, 'index'])->name('center-stock');
    
        Route::get('center-stock/product-ledger/{product}', [CenterWiseStockReportController::class, 'productLedger'])->name('center-stock.product-ledger');
        
        Route::get('center-stock/center-detail/{product}', [CenterWiseStockReportController::class, 'centerStockDetail'])->name('center-stock.center-detail');
        
        Route::get('center-stock/expired-info/{product}', [CenterWiseStockReportController::class, 'expiredInfo'])->name('center-stock.expired-info');
        
        Route::get('center-stock/serial-info/{lotNo}', [CenterWiseStockReportController::class, 'serialInfo'])->name('center-stock.serial-info');


       

    });

});