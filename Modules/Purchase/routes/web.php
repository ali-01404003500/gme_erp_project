<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Modules\Purchase\Controllers\OfficePurchaseController;
use Modules\Purchase\Controllers\PurchaseOrderController;
use Modules\Purchase\Controllers\PurchaseReportController;
use Modules\Purchase\Controllers\PurchaseReturnApproveController;
use Modules\Purchase\Controllers\PurchaseReturnController;
use Modules\Purchase\Controllers\RequisitionController;
use Modules\Purchase\Controllers\RequisitionImportController;
use Modules\Purchase\Controllers\RequisitionReceiveController;
use Modules\Purchase\Controllers\SupplierController;
use Modules\Purchase\Controllers\VendorController;

Route::group(['middleware'=>'auth', 'prefix' => 'purchase', 'as' => 'purchase.'],function () {

        Route::resource('suppliers', SupplierController::class);
        Route::post('suppliers-insert', [SupplierController::class, 'insertFromCSV'])->name('suppliers-insert');
        Route::get('suppliers-download', [SupplierController::class, 'downloadSampleCSV'])->name('suppliers-download');

        Route::resource('vendors', VendorController::class);
        Route::post('vendors-insert', [VendorController::class, 'insertFromCSV'])->name('vendors-insert');
        Route::get('vendors-download', [VendorController::class, 'downloadSampleCSV'])->name('vendors-download');
        Route::get('get-vendors', [VendorController::class, 'getAllVendors'])->name('get-vendors');

        Route::resource('requisitions', RequisitionController::class);
        Route::get('requisitions/approve/{id}', [RequisitionController::class, 'approve'])->name('requisitions.approve');
        Route::put('requisitions/{id}/approve', [RequisitionController::class, 'approveStore'])->name('requisitions.approveStore');
        Route::get('requisitions/receive/{id}', [RequisitionReceiveController::class, 'edit'])->name('requisitions.receive');
        Route::post('requisitions/receive', [RequisitionReceiveController::class, 'store'])->name('requisitions.receive.store');
        Route::post('requisitions/receive/serial', [RequisitionReceiveController::class, 'storeSerial'])->name('requisitions.storeSerial')->withoutMiddleware([VerifyCsrfToken::class]);
        Route::post('requisitions/receive/batch', [RequisitionReceiveController::class, 'storeBatch'])->name('requisitions.storeBatch')->withoutMiddleware([VerifyCsrfToken::class]);
        Route::get('requisitions/received/{id}', [RequisitionReceiveController::class, 'show'])->name('requisitions.received');

        Route::get('purchase/requisitions/{requisition_id}/serials', [RequisitionReceiveController::class, 'getSerials'])->name('requisitions.serials');
        Route::get('purchase/requisitions/{requisition_id}/batches', [RequisitionReceiveController::class, 'batches'])->name('requisitions.batches');

        Route::resource('orders', PurchaseOrderController::class);
        Route::resource('offices', OfficePurchaseController::class);
        Route::resource('offices', OfficePurchaseController::class);
        Route::get('offices/{id}/approve', [OfficePurchaseController::class, 'approve'])->name('offices.approve');
        Route::put('offices/{id}/approve', [OfficePurchaseController::class, 'approveStore'])->name('offices.approveStore');



        Route::get('get-product-list', [RequisitionController::class, 'getProduct'])->name('get.product.list');
        Route::get('get-supplier', [PurchaseOrderController::class, 'getSupplierData'])->name('get.supplier-data');
        Route::get('get-product', [PurchaseOrderController::class, 'getProductData'])->name('product-data');
        Route::get('get-product-brand-wise', [PurchaseOrderController::class, 'getBrandData'])->name('product-data.brand.wise');

        Route::resource('returns', PurchaseReturnController::class);
        Route::get('approve/{id}', [PurchaseReturnApproveController::class, 'create'])->name('returns.approve');
        Route::post('approve', [PurchaseReturnApproveController::class, 'store'])->name('returns.approve.store');
        Route::get('approve/{id}/approve-show', [PurchaseReturnApproveController::class, 'show'])->name('returns.approve.show');
        Route::get('returns-approve-details', [PurchaseReturnApproveController::class, 'details'])->name('returns.approve.details');
        Route::get('{product_id}/{requisition_id}/select-stock', [PurchaseReturnApproveController::class, 'selectStock'])->name('returns.select-stock');
        Route::get('returns/print/{id}', [PurchaseReturnController::class, 'print'])->name('returns.print');

        Route::group(['prefix' => 'requisition', 'as'=> 'requisition.'], function () {
                Route::get('import', [RequisitionImportController::class, 'import'])->name('import');
                Route::post('import/process', [RequisitionImportController::class, 'processImport'])->name('import.process');
                Route::get('import/template', [RequisitionImportController::class, 'downloadTemplate'])->name('import.template');
                Route::post('import/validate', [RequisitionImportController::class, 'validateImport'])->name('import.validate');
                Route::get('import/sample', [RequisitionImportController::class, 'generateSampleData'])->name('import.sample');
                
                // Export routes
                Route::get('export', [RequisitionImportController::class, 'exportExisting'])->name('export');
                
                // Helper routes
                Route::get('available-names', [RequisitionImportController::class, 'getAvailableNames'])->name('available.names');
                Route::get('create-missing', [RequisitionImportController::class, 'createMissingEntities'])->name('create.missing');
                Route::post('create-missing/store', [RequisitionImportController::class, 'storeMissingEntities'])->name('create.missing.store');
                Route::post('create-missing/bulk', [RequisitionImportController::class, 'bulkCreateMissingEntities'])->name('create.missing.bulk');
        });

        Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
                Route::get('/', [PurchaseReportController::class, 'index'])->name('index');                
                Route::get('/payment-details/{requisitionId}', [PurchaseReportController::class, 'getPaymentDetails'])
                ->name('payment-details');
                
                Route::get('/money-receipt/{paymentId}', [PurchaseReportController::class, 'showMoneyReceipt'])
                ->name('money-receipt');
                
                Route::post('/custom-export', [PurchaseReportController::class, 'customExport'])
                ->name('custom-export');
                
                Route::post('/update-field', [PurchaseReportController::class, 'updateField'])
                ->name('update-field');
        });

});