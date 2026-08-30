<?php

use Illuminate\Support\Facades\Route;
use Modules\Import\Controllers\PurchaseOrderController;

Route::middleware(['auth'])->prefix('import')->name('import.')->group(function () {

Route::resource('purchase-orders', PurchaseOrderController::class);

// Route::post('/purchase-orders/{purchaseOrder}/submit-for-approval', [PurchaseOrderController::class, 'submitForApproval'])
//     ->name('purchase-orders.submit-for-approval');
// Route::post('/purchase-orders/{purchaseOrder}/approve', [PurchaseOrderController::class, 'approve'])
//     ->name('purchase-orders.approve');
// Route::post('/purchase-orders/{purchaseOrder}/reject', [PurchaseOrderController::class, 'reject'])
//     ->name('purchase-orders.reject');
// Route::post('/purchase-orders/{purchaseOrder}/send-to-supplier', [PurchaseOrderController::class, 'sendToSupplier'])
//     ->name('purchase-orders.send-to-supplier');
// Route::post('/purchase-orders/{purchaseOrder}/mark-acknowledged', [PurchaseOrderController::class, 'markAcknowledged'])
//     ->name('purchase-orders.mark-acknowledged');

// ভবিষ্যতে এখানে যোগ হবে: proforma-invoices, payments, shipments, customs-clearances, grns, landed-cost
});


