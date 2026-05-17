<?php

use Illuminate\Support\Facades\Route;
use Modules\Services\Controllers\EmergencyNoteController;
use Modules\Services\Controllers\InstallationReportController;
use Modules\Services\Controllers\MonthlyServiceReportController;
use Modules\Services\Controllers\ServiceAssignController;
use Modules\Services\Controllers\ServiceBillController;
use Modules\Services\Controllers\ServiceController;
use Modules\Services\Controllers\ServiceDocumentEntryController;
use Modules\Services\Controllers\ServiceExplorerReportController;
use Modules\Services\Controllers\ServiceMyTaskController;
use Modules\Services\Controllers\ServiceQuotationController;
use Modules\Services\Controllers\ServiceReportController;
use Modules\Services\Controllers\Settings\ProblemTypeController;
use Modules\Services\Controllers\Settings\ServiceTypeController;
use Modules\Services\Controllers\WarrantyReportController;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;

Route::group(['middleware'=>'auth', 'prefix' => 'Services', 'as' => 'services.'],function () {

        Route::resource('service', ServiceController::class); 
        Route::put('services-action-update/{id}', [ServiceController::class, 'updateAction'])->name('services-action-update');
        Route::resource('service-assign', ServiceAssignController::class); 
        Route::post('assign-engineer', [ServiceAssignController::class, 'assignEngineer'])->name('assign-engineer');
        Route::get('get-token-details/{id}', [ServiceAssignController::class, 'getTokenDetails'])->name('get-token-details');
        Route::resource('document-entries', ServiceDocumentEntryController::class);
        Route::resource('quotations', ServiceQuotationController::class);
        Route::get('quotations/sales-order/{id}', [ServiceQuotationController::class, 'salesOrder'])->name('quotations.sales.order');
        Route::get('quotations/print/{id}', [ServiceQuotationController::class, 'print'])->name('quotations.print');
        Route::get('service-autocomplete-service-id', [ServiceQuotationController::class, 'serviceAutocomplete']) ->name('service-autocomplete.service-id');
        Route::get('service-customer-id', [ServiceQuotationController::class, 'getCustomerByService']) ->name('service-customer-id');

        // Route::resource('emergency-notes', EmergencyNoteController::class); 
        Route::resource('service-my-task', ServiceMyTaskController::class);
        Route::get('service-autocomplete-products', [ServiceMyTaskController::class, 'productAutocomplete']) ->name('service-autocomplete.products');
        Route::get('solution-verification', [ServiceMyTaskController::class, 'solutionVerification'])->name('service-my-task.solution-verification');
        Route::put('solution-verification/{id}', [ServiceMyTaskController::class, 'solutionVerificationStore'])->name('service-my-task.solution-verification-store');

        Route::resource('service-bills', ServiceBillController::class);

     
        // OTP Routes for Service Bill
        Route::post('send-otp', [ServiceBillController::class, 'sendOtp'])->name('send-otp');
        Route::post('verify-otp', [ServiceBillController::class, 'verifyOtp'])->name('verify-otp');

        Route::get('service-get-invoices', [ServiceController::class, 'getInvoices'])->name('service-get-invoices');
        Route::get('get-invoice-by-serial', [ServiceController::class, 'getInvoiceBySerial'])->name('get.invoice.by.serial');

        Route::get('service-get-products', [ServiceController::class, 'getProducts'])->name('service-get-products');
        Route::get('service-get-serial-ids', [ServiceController::class, 'getSerialIds'])->name('service-get-serial-ids');
        Route::get('service-get-quantity', [ServiceController::class, 'getQuantity'])->name('service-get-quantity');
        Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {

            Route::resource('service-types', ServiceTypeController::class);
            Route::get('problem-types', [ProblemTypeController::class, 'search'])->name('problem-types.search');
            Route::post('problem-types', [ProblemTypeController::class, 'store'])->name('problem-types.store');
        });

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('service-reports', [ServiceReportController::class, 'index'])->name('service-reports');
            Route::post('/update-solution/{id}', [ServiceReportController::class, 'solutionVerificationStore'])->name('update-solution');

            Route::get('warranty-check', [WarrantyReportController::class, 'index'])->name('warranty-check');
            Route::get('warranty-check/customer-serials', [WarrantyReportController::class, 'getCustomerSerials'])->name('warranty-check.customer-serials');
            Route::get('warranty-check/by-customer', [WarrantyReportController::class, 'getWarrantyByCustomer'])->name('warranty-check.by-customer');
            Route::get('warranty-check/by-serial', [WarrantyReportController::class, 'getWarrantyBySerial'])->name('warranty-check.by-serial');

            Route::get('service-explorer-reports', [ServiceExplorerReportController::class, 'index'])->name('service-explorer-reports');
            Route::get('monthly-service-reports', [MonthlyServiceReportController::class, 'index'])->name('monthly-service-reports');

            Route::get('monthly-service-reports/details', [MonthlyServiceReportController::class, 'getEngineerDetails'])->name('monthly-service-reports.details');
            Route::get('installation-reports', [InstallationReportController::class, 'index'])->name('installation-reports'); 
            Route::get('installation-report-details/{id}', [InstallationReportController::class, 'details'])->name('installation-report-details'); 
        });

            

            
});