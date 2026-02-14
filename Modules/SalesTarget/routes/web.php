<?php

use Illuminate\Support\Facades\Route;
use Modules\SalesTarget\Controllers\AchievementBasedSalaryPolicyController;
use Modules\SalesTarget\Controllers\TargetController;
use Modules\SalesTarget\Controllers\SalesIncentiveController;

Route::group(['middleware'=>'auth', 'prefix' => 'sales_target', 'as' => 'sales_target.'], function () {
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::resource('achievement-based-salary-policy', AchievementBasedSalaryPolicyController::class);
        Route::resource('target', TargetController::class);
        
        // New Route for Sales Incentive Slab Setup
        Route::resource('incentives', SalesIncentiveController::class);
    });
});