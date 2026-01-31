<?php

use Illuminate\Support\Facades\Route;
use Modules\Legal\Controllers\LegalBillEntryController;
use Modules\Legal\Controllers\LegalEntryController;


Route::group(['middleware' => 'auth', 'prefix' => 'legal', 'as' => 'legal.'], function () {

        Route::resource('legal-entries', LegalEntryController::class);
        Route::get('get-legal-details', [LegalEntryController::class, 'getForScheduleUpdate'])->name('get-legal-details');
        Route::get('legal-entries-reports', [LegalEntryController::class, 'report'])->name('reports');
        // Route::get('legal-entries/get-schedule-data/{id}', [LegalEntryController::class, 'getScheduleData'])->name('legal-entries.get-schedule-data');
        Route::get('legal-schedule/{id}', [LegalEntryController::class, 'getScheduleData']);
        Route::post('legal-schedule/update', [LegalEntryController::class, 'updateSchedule']);
        Route::get('hajira-remarks/{id}', [LegalEntryController::class, 'getHajiraRemarks']);
        Route::get('legal-entries-approve/{id}', [LegalEntryController::class, 'approve'])->name('legal-entries.approve');
        Route::get('legal-entries-deny/{id}', [LegalEntryController::class, 'deny'])->name('legal-entries.deny');
        Route::resource('legal-bill-entries', LegalBillEntryController::class);


});