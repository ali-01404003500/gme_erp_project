<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Modules\CMS\Controllers\ApplicationEntryController;
use Modules\CMS\Controllers\DocumentEntryController;
use Modules\CMS\Controllers\DocumentHeadController;
use Modules\CMS\Controllers\DocumentTypeController;

Route::group(['middleware'=>'auth', 'prefix' => 'cms', 'as' => 'cms.'],function () {

     /* Document Entry */
      Route::resource('document-entries', DocumentEntryController::class);
      // In routes/web.php or routes/api.php (if you're using API)
        Route::get('get-document-heads', [DocumentEntryController::class, 'getDocumentHeads'])->name('document-heads.list');

      /* Document Type */
      Route::resource('document-types', DocumentTypeController::class);
      /* Document Head */
      Route::resource('document-heads', DocumentHeadController::class);

      Route::resource('application-entries', ApplicationEntryController::class);
      Route::put('application-entries/approved/{id}', [ApplicationEntryController::class, 'approved'])->name('application-entries.approved');
      Route::put('application-entries/handover/{id}', [ApplicationEntryController::class, 'handover'])->name('application-entries.handover');
      Route::put('application-entries/received/{id}', [ApplicationEntryController::class, 'received'])->name('application-entries.received');
      Route::put('application-entries/deny/{id}', [ApplicationEntryController::class, 'deny'])->name('application-entries.deny');

});