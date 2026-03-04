<?php

use Illuminate\Support\Facades\Route;
use Modules\CRM\Controllers\Customer\BrokerController;
use Modules\CRM\Controllers\Customer\CustomerController;
use Modules\CRM\Controllers\Customer\DailyCallController;
use Modules\CRM\Controllers\Customer\DailyCreditCallController;
use Modules\CRM\Controllers\Customer\Settings\CustomerRatingController;
use Modules\CRM\Controllers\Customer\Settings\CustomerShippingController;
use Modules\CRM\Controllers\Customer\Settings\CustomerTypeController;
use Modules\CRM\Controllers\Customer\Settings\DocumentHeadController;
use Modules\CRM\Controllers\Customer\Settings\DocumentTypeController;
use Modules\CRM\Controllers\Customer\Settings\PercentageTypeController;
use Modules\CRM\Controllers\Reports\CustomerBalanceReportController;
use Modules\CRM\Controllers\Reports\CustomerMachineCodeReportController;

Route::group(['middleware'=>'auth', 'prefix' => 'crm', 'as' => 'crm.'],function () {

    Route::resource('customers', CustomerController::class);
    Route::get('customers-approve/{id}', [CustomerController::class, 'approve'])->name('customers.approve');
    Route::get('customers-deny/{id}', [CustomerController::class, 'deny'])->name('customers.deny');

    Route::post('customers-insert', [CustomerController::class, 'insertFromCSV'])->name('customers-insert');
    Route::get('customers-download', [CustomerController::class, 'downloadSampleCSV'])->name('customers-download');

    Route::get('customers/{id}/settings', [CustomerController::class, 'customerSettings'])->name('customers.settings');
    Route::put('customers/{id}/settings', [CustomerController::class, 'customerSettingStore'])->name('customers.settings.store');
    Route::get('get-broker-details', [CustomerController::class, 'getBrokerDetails'])->name('get-broker-details');
    Route::get('get-broker-with-settings/{id}', [CustomerController::class, 'editBrokerWithSettings'])->name('get-broker-with-settings');
    Route::post('update-broker-details/{id}', [CustomerController::class, 'updateBrokerWithSettings'])->name('update-broker-details');

    Route::get('customer-count', [CustomerController::class, 'countCustomer'])->name('customer.count');
    Route::get('get-customers', [CustomerController::class, 'getCustomers'])->name('get-customers');    
    Route::get('autocomplete-customers', [CustomerController::class, 'customerAutocomplete']) ->name('autocomplete.customers');

    /* Customer Type */
    Route::resource('customer-types', CustomerTypeController::class);

    /* Customer Rating */
    Route::resource('customer-ratings', CustomerRatingController::class);

    /* Customer Shipping */
    Route::resource('customer-shippings', CustomerShippingController::class);

    Route::resource('percentage-types', PercentageTypeController::class);

    /* Broker */
    Route::resource('brokers', BrokerController::class);
     Route::get('brokers-approve/{id}', [BrokerController::class, 'approve'])->name('brokers.approve');
    Route::get('brokers-deny/{id}', [BrokerController::class, 'deny'])->name('brokers.deny');
    Route::post('brokers-insert', [BrokerController::class, 'insertFromCSV'])->name('brokers-insert');
    Route::get('brokers-download', [BrokerController::class, 'downloadSampleCSV'])->name('brokers-download');


    Route::resource('daily-calls', DailyCallController::class);

    Route::resource('daily-credit-calls', DailyCreditCallController::class);
    Route::get('daily-credit-calls-legal', [DailyCreditCallController::class, 'legal'])->name('daily-credit-calls.legal');
    Route::post('daily-credit-calls-legal', [DailyCreditCallController::class, 'legalStore'])->name('daily-credit-calls.legalStore');

    Route::prefix('reports')->name('reports.')->group(function () {

        Route::get('customer-machine-code', [CustomerMachineCodeReportController::class, 'index'])->name('customer-machine-code');
        Route::get('customer-balance-details', [CustomerBalanceReportController::class, 'index'])
        ->name('customer-balance-details');



    });

});