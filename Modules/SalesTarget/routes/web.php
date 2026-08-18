<?php

use Illuminate\Support\Facades\Route;
use Modules\SalesTarget\Controllers\AchievementBasedSalaryPolicyController;
use Modules\SalesTarget\Controllers\TargetController;
use Modules\SalesTarget\Controllers\SalesIncentiveController;

Route::group(['middleware' => 'auth', 'prefix' => 'sales_target', 'as' => 'sales_target.'], function () {

    // Move this OUTSIDE the settings group
    Route::get('performance/achievement', [TargetController::class, 'achievement'])->name('perfomence.achievement');

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::resource('achievement-based-salary-policy', AchievementBasedSalaryPolicyController::class);
        Route::resource('target', TargetController::class); 
    });

    Route::get('salesIncentives/incentives', [TargetController::class, 'incentives'])->name('salesIncentives.incentives.index');
});
