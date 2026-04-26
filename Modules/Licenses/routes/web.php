<?php

use Illuminate\Support\Facades\Route;
use Modules\Licenses\Controllers\CBCLicenseRequisitionController;
use Modules\Licenses\Controllers\CBCSmsController;
use Modules\Licenses\Controllers\DongleOrSerialEntryController;
use Modules\Licenses\Controllers\LicenseReportController;
use Modules\Licenses\Controllers\USGOrOPGLicenseRequisitionController;
use Modules\Licenses\Controllers\USGOrOPGSmsController;

Route::group(['middleware'=>'auth', 'prefix' => 'Licenses', 'as' => 'licenses.'],function () {

        Route::resource('dongle-or-serial-entries', DongleOrSerialEntryController::class);
        Route::get('dongle-or-serial-entries-dropdown', [DongleOrSerialEntryController::class, 'dropdown'])->name('dongle-or-serial-entries.dropdown');
        Route::resource('usg-opg-license-requisitions', USGOrOPGLicenseRequisitionController::class);
        Route::post('dongle-or-serial-entries/store-dongle', [DongleOrSerialEntryController::class, 'storeDongle'])->name('dongle-or-serial-entries.store-dongle');
        Route::get('usg-opg-get-dongle-ids', [USGOrOPGLicenseRequisitionController::class, 'getDongleIds'])->name('usg-opg.getDongleIds');
        Route::get('usg-opg-get-notes', [USGOrOPGLicenseRequisitionController::class, 'getNotes'])->name('usg-opg.getNotes');
        Route::get('usg-opg-license-requisitions-verify', [USGOrOPGLicenseRequisitionController::class, 'verifylist'])->name('usg-opg-license-requisitions.verifylist');
        Route::get('usg-opg-license-requisitions/{id}/approve', [USGOrOPGLicenseRequisitionController::class, 'approve'])->name('usg-opg-license-requisitions.approve');
        Route::put('usg-opg-license-requisitions/{id}/approve', [USGOrOPGLicenseRequisitionController::class, 'approveStore'])->name('usg-opg-license-requisitions.approveStore');

        Route::resource('usg-opg-sms', USGOrOPGSmsController::class);
        Route::resource('cbc-sms', CBCSmsController::class);
        Route::resource('reports', LicenseReportController::class);

        Route::resource('cbc-license-requisitions', CBCLicenseRequisitionController::class);
        Route::get('get-dongle-ids', [CBCLicenseRequisitionController::class, 'getDongleIds'])->name('cbc.getDongleIds');
        Route::get('get-notes', [CBCLicenseRequisitionController::class, 'getNotes'])->name('cbc.getNotes');
        Route::get('cbc-license-requisitions-verify', [CBCLicenseRequisitionController::class, 'verifylist'])->name('cbc-license-requisitions.verifylist');

        Route::get('cbc-license-requisitions/{id}/approve', [CBCLicenseRequisitionController::class, 'approve'])->name('cbc-license-requisitions.approve');
        Route::put('cbc-license-requisitions/{id}/approve', [CBCLicenseRequisitionController::class, 'approveStore'])->name('cbc-license-requisitions.approveStore');

        Route::get('dongle-or-serial-autocomplete-customers', [DongleOrSerialEntryController::class, 'customerAutocomplete']) ->name('dongle-or-serial-autocomplete.customers');
        Route::get('dongle-or-serial-autocomplete-products', [DongleOrSerialEntryController::class, 'productAutocomplete']) ->name('dongle-or-serial-autocomplete.products');
        Route::get('dongle-or-serial-autocomplete-dongles', [DongleOrSerialEntryController::class, 'dongleAutocomplete']) ->name('dongle-or-serial-autocomplete.dongles');
 
        Route::get('sales-orders-autocomplete-customers', [LicenseReportController::class, 'customerAutocomplete']) ->name('license-report-autocomplete.customers');

        
        

});