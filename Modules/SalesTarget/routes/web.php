<?php

use Illuminate\Support\Facades\Route;
use Modules\SalesTarget\Controllers\AchievementBasedSalaryPolicyController;
use Modules\SalesTarget\Controllers\TargetController;
use Modules\SalesTarget\Controllers\SalesIncentiveController;
use Modules\SalesTarget\Controllers\SalesIncentiveSlabController;
use Modules\SalesTarget\Controllers\SalesSalaryBracketController;
use Modules\SalesTarget\Controllers\SalesTargetController;
use Modules\SalesTarget\Controllers\SalesTargetSlabController;

Route::group(['middleware' => 'auth', 'prefix' => 'sales_target', 'as' => 'sales_target.'], function () {

    // Move this OUTSIDE the settings group
    Route::get('performance/achievement', [TargetController::class, 'achievement'])->name('perfomence.achievement');

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::resource('achievement-based-salary-policy', AchievementBasedSalaryPolicyController::class);
        Route::resource('target', TargetController::class); 
    });

    Route::get('salesIncentives/incentives', [TargetController::class, 'incentives'])->name('salesIncentives.incentives.index');



        
    Route::prefix('sales-targets')->group(function () {
        Route::get('/', [SalesTargetController::class, 'index'])->name('sales-targets.index');
        Route::post('/', [SalesTargetController::class, 'store'])->name('sales-targets.store');
        Route::post('/{target}/lock', [SalesTargetController::class, 'lock'])->name('sales-targets.lock');
        Route::post('/{target}/full-honor', [SalesTargetController::class, 'fullHonor'])->name('sales-targets.full-honor');
    });

    Route::resource('sales-target-slabs', SalesTargetSlabController::class);
    Route::resource('sales-incentive-slabs', SalesIncentiveSlabController::class)->parameters(['sales-incentive-slabs' => 'salesIncentiveSlab']);
    Route::resource('sales-salary-brackets', SalesSalaryBracketController::class)->parameters(['sales-salary-brackets' => 'salesSalaryBracket']);



});




