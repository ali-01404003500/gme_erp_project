<?php

use Modules\Sales\Controllers\BrandSupplierSalesReportController;
use Illuminate\Support\Facades\Route;
use Modules\Account\Controllers\CustomerController;
use Modules\Account\Controllers\ProductController;
use Modules\Inventory\Models\Product\Settings\Brand;
use Modules\Sales\Controllers\BackupChallanController;
use Modules\Sales\Controllers\BackupChallanDeliveryController;
use Modules\Sales\Controllers\BrokerCommissionReportController;
use Modules\Sales\Controllers\CourierController;
use Modules\Sales\Controllers\DeliveryController;
use Modules\Sales\Controllers\FakeInvoiceController;
use Modules\Sales\Controllers\FakeSalesReportController;
use Modules\Sales\Controllers\FreeSalesController;
use Modules\Sales\Controllers\QuotationController;
use Modules\Sales\Controllers\SalesCommissionController;
use Modules\Sales\Controllers\SalesOrderController;
use Modules\Sales\Controllers\SalesOrderDeliveryController;
use Modules\Sales\Controllers\SalesOrderImportController;
use Modules\Sales\Controllers\SalesReportController;
use Modules\Sales\Controllers\SalesRequisitionController;
use Modules\Sales\Controllers\SalesReturnController;
use Modules\Sales\Controllers\ShipmentExplorerReportController;
use Modules\Sales\Controllers\ShipmentVerifyController;
 

Route::group(['middleware' => 'auth', 'prefix' => 'sales', 'as' => 'sales.'], function () {

    Route::resource('sales-orders', SalesOrderController::class);
    Route::get('branch-by-bank', [SalesOrderController::class, 'getBranchByBank'])->name('branch-by-bank');
    Route::get('sales-orders-count', [SalesOrderController::class, 'countSalesOrder'])->name('sales-orders.count');
    Route::get('total-sales-count', [SalesOrderController::class, 'countTotalSales'])->name('total-sales.count');
    Route::get('sales-orders-calculate-discount', [SalesOrderController::class, 'calculateDiscountForProducts'])->name('sales-orders.calculate-discount');
    Route::get('sales-orders-product-free-sales-invoice/{id}', [SalesOrderController::class, 'productFreeSalesInvoice'])->name("sales-orders.product-free-sales-invoice");
    Route::post('sales-orders-product-free-sales-invoice/{id}', [SalesOrderController::class, 'storeProductFreeSalesInvoice'])->name("sales-orders.product-free-sales-invoice.store");
    Route::get('sales-orders-product-free-sales-invoice/view/{id}', [SalesOrderController::class, 'viewProductFreeSalesInvoice'])->name("sales-orders.product-free-sales-invoice.view");
    Route::get('sales-orders-autocomplete-customers', [SalesOrderController::class, 'customerAutocomplete']) ->name('sales-orders-autocomplete.customers');
    Route::get('sales-orders-autocomplete-products', [SalesOrderController::class, 'productAutocomplete']) ->name('sales-orders-autocomplete.products');

    // Sales Order Import Routes
    Route::group(['prefix' => 'sales-order-import'], function () {
        Route::get('/', [SalesOrderImportController::class, 'index'])->name('sales-order-import.index');
        Route::post('/import', [SalesOrderImportController::class, 'import'])->name('sales-order-import.import');
        Route::post('/bulk-import', [SalesOrderImportController::class, 'bulkImport'])->name('sales-order-import.bulk-import');
        Route::get('/template', [SalesOrderImportController::class, 'getTemplate'])->name('sales-order-import.template');
        Route::get('/download-template', [SalesOrderImportController::class, 'downloadTemplate'])->name('sales-order-import.download-template');
        Route::post('/validate-json', [SalesOrderImportController::class, 'validateJson'])->name('sales-order-import.validate-json');
        Route::post('/validate-file', [SalesOrderImportController::class, 'validateFile'])->name('sales-order-import.validate-file');
    });


    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('sales-report', [SalesReportController::class, 'index'])->name('sales-report');
        Route::get('fake-sales', [FakeSalesReportController::class, 'index'])->name('fake-sales');
        Route::get('broker-commissions', [BrokerCommissionReportController::class, 'index'])->name('broker-commissions');
        Route::get('brand-supplier-sales-report', [BrandSupplierSalesReportController::class, 'index'])->name('brand-supplier-sales-report');
        Route::get('shipment-explorer', [ShipmentExplorerReportController::class, 'index'])->name('shipment-explorer');

        Route::get(
            'shipment-explorer/verification-details/{shipmentVerifyId}',
            [ShipmentExplorerReportController::class, 'getVerificationDetails']
        )
            ->name('shipment-explorer.verification-details');

        Route::post(
            'shipment-explorer/verification-status/{shipmentVerifyId}',
            [ShipmentExplorerReportController::class, 'updateVerificationStatus']
        )
            ->name('shipment-explorer.verification-status');

    });

    Route::resource('sales-order-deliveries', SalesOrderDeliveryController::class);
    Route::get('sales-orders-autocomplete-employees', [SalesOrderController::class, 'employeeAutocomplete']) ->name('sales-orders-autocomplete.employees'); 
    Route::resource('fake-invoices', FakeInvoiceController::class);
    Route::get('sales-orders-autocomplete-invoice', [SalesOrderController::class, 'invoiceAutocomplete']) ->name('sales-orders-autocomplete.invoice'); 


    Route::resource('sales-commissions', SalesCommissionController::class);

    Route::post('sales-commissions/verify', [SalesCommissionController::class, 'verify'])->name('sales-commissions.verify');

    // sales.sales-order-deliveries.select-stock

    Route::get('sales-order-deliveries/{product_id}/select-stock', [SalesOrderDeliveryController::class, 'selectStock'])->name('sales-order-deliveries.select-stock');

    Route::resource('sales-requisitions', SalesRequisitionController::class);
    Route::get('sales-requisitions/{id}/save-to-sales-order', [SalesRequisitionController::class, 'saveToSalesOrder'])->name('sales-requisitions.save-to-sales-order');
    Route::resource('quotations', QuotationController::class);
    Route::resource('backup-challans', BackupChallanController::class);
    Route::get('backup-challans/approve/{id}', [BackupChallanController::class, 'approve'])->name('backup-challans.approve');
    Route::put('backup-challans/{id}/approve', [BackupChallanController::class, 'approveStore'])->name('backup-challans.approveStore');
    Route::get('backup-challan/sales-order/{id}', [BackupChallanController::class, 'salesOrder'])->name('backup-challan.sales.order');

    Route::get('backup-challans/{id}/save-to-sales-order', [BackupChallanController::class, 'saveToSalesOrder'])->name('backup-challans.save-to-sales-order');
    Route::get('backup-challans/{id}/send-to-delivery', [BackupChallanController::class, 'sendToDelivery'])->name('backup-challans.send-to-delivery');

    Route::get('quotations/approve/{id}', [QuotationController::class, 'approval'])->name('quotations.approve');
    Route::put('quotations/{id}/approve', [QuotationController::class, 'approveStore'])->name('quotations.approveStore');
    Route::get('quotations/sales-order/{id}', [QuotationController::class, 'salesOrder'])->name('quotations.sales.order');
    Route::get('quotations/print/{id}', [QuotationController::class, 'print'])->name('quotations.print');

    Route::post('quotations/pdf', [QuotationController::class, 'PDF'])->name('quotations.pdf');
    Route::resource('couriers', CourierController::class);

    Route::get('get-customer-setting', [SalesOrderController::class, 'getCustomerSetting'])->name('get.customer.setting');
    Route::get('get-sales-discount', [SalesOrderController::class, 'getSalesDiscount'])->name('get-sales-discount');

    Route::resource('deliveries', DeliveryController::class);
    Route::get('deliveries-details', [DeliveryController::class, 'details'])->name('deliveries.details');

    Route::resource('shipment-verifies', ShipmentVerifyController::class);
    Route::post('shipment-verifie-send-sms', [ShipmentVerifyController::class, 'sendSms'])->name('shipment-verifies.send-sms');

    Route::resource('sales-returns', SalesReturnController::class);
    Route::get('approve/{id}', [SalesReturnController::class, 'approve'])->name('sales-returns.approve');
    Route::put('approve/{id}', [SalesReturnController::class, 'approveStore'])->name('sales-returns.approve.store');
    Route::get('{product_id}/{sales_order_id}/{sales_return_id}/select-stock', [SalesReturnController::class, 'selectStock'])->name('sales-returns.select-stock');

    Route::resource('condition-amount-collects', \Modules\Sales\Controllers\ConditionAmountCollectController::class);
    Route::get('condition-amount-collects-received-details', [\Modules\Sales\Controllers\ConditionAmountCollectController::class, 'getReceivedDetails'])->name('condition-amount-collects.received-details');
    // POST for action
    Route::post('condition-amount-collects-received-back', [\Modules\Sales\Controllers\ConditionAmountCollectController::class, 'receivedBack'])->name('condition-amount-collects.received-back');
    Route::get('condition-amount-collects-approved-list', [\Modules\Sales\Controllers\ConditionAmountCollectController::class, 'approvedList'])->name('condition-amount-collects.approved-list');
    Route::post('condition-amount-collects-approve', [\Modules\Sales\Controllers\ConditionAmountCollectController::class, 'approve'])->name('condition-amount-collects.approve');
    Route::get('condition-amount-collects/{id}/claim-pdf', [\Modules\Sales\Controllers\ConditionAmountCollectController::class, 'claimPdf'])->name('condition-amount-collects.claim-pdf');
    Route::post('condition-amount-collects/send-bulk-message', [\Modules\Sales\Controllers\ConditionAmountCollectController::class, 'sendBulkMessage'])->name('condition-amount-collects.send-bulk-message');
    Route::post('condition-amount-collects/bulk-receive', [\Modules\Sales\Controllers\ConditionAmountCollectController::class, 'bulkReceive'])->name('condition-amount-collects.bulk-receive');

 

});