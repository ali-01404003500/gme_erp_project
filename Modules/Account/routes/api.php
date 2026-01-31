<?php

use Illuminate\Support\Facades\Route;
use Modules\Account\Controllers\Api\BankAccountControllerApi;
use Modules\Account\Controllers\Api\BankApiController;
use Modules\Account\Controllers\Api\BankBranchApiController;
use Modules\Account\Controllers\Api\CollectionControllerApi;
use Modules\Account\Controllers\Api\IOURequisitionEntryControllerApi;

Route::group(['middleware' => ['auth:api'], 'prefix' => 'account', 'as' => 'account.'], function () {
    Route::get('invoice-wise-collections-balance/{id}', [\Modules\Account\Controllers\Api\InvoiceWiseCollectionControllerApi::class, 'getBalance']);
    Route::apiResource('invoice-wise-collections', \Modules\Account\Controllers\Api\InvoiceWiseCollectionControllerApi::class);
    Route::group(['prefix' => 'account-setup', 'as' => 'account-setup.'], function () {
        Route::apiResource('bank-accounts', BankAccountControllerApi::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('bank-account-data', [BankAccountControllerApi::class, 'getAccounts'])->name('bank-accounts.get-accounts');

        Route::apiResource('banks', BankApiController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('all-banks', [BankApiController::class, 'getAllBanks'])->name('banks.get-all-banks');

        Route::apiResource('bank-branches', BankBranchApiController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('get-bank-branches', [BankBranchApiController::class, 'getBranches'])->name('get-bank-branches');

    });

    Route::apiResource('collections', CollectionControllerApi::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::get('collection-accounts-by-type', [CollectionControllerApi::class, 'getAccountsByType'])->name('collections.get-accounts-by-type');
    Route::get('collection-get-balance', [CollectionControllerApi::class, 'getBalance'])->name('collections.get-balance');

    Route::apiResource('iou-requisition-entries', IOURequisitionEntryControllerApi::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    // Route::post('iou-requisition-entries/{id}/mark-as-paid', [IOURequisitionEntryControllerApi::class, 'markAsPaid'])->name('iou-requisition-entries.mark-as-paid');
    // Route::post('iou-requisition-entries/{id}/process-return', [IOURequisitionEntryControllerApi::class, 'processReturn'])->name('iou-requisition-entries.process-return');
    // Route::post('iou-requisition-entries/send-otp', [IOURequisitionEntryControllerApi::class, 'sendOTP'])->name('iou-requisition-entries.send-otp');
    // Route::post('iou-requisition-entries/verify-otp', [IOURequisitionEntryControllerApi::class, 'verifyOTP'])->name('iou-requisition-entries.verify-otp');
    // Route::post('iou-requisition-entries/confirm-payment', [IOURequisitionEntryControllerApi::class, 'confirmPayment'])->name('iou-requisition-entries.confirm-payment');
    // Route::post('iou-requisition-entries/return-bill', [IOURequisitionEntryControllerApi::class, 'returnBill'])->name('iou-requisition-entries.return-bill');

});