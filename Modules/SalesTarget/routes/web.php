<?php

use Illuminate\Support\Facades\Route;
use Modules\SalesTarget\Controllers\AchievementBasedSalaryPolicyController;
use Modules\SalesTarget\Controllers\TargetController;

Route::group(['middleware'=>'auth', 'prefix' => 'sales_target', 'as' => 'sales_target.'],function () {
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        //Route::get('achievement-based-salary-policy', [AchievementBasedSalaryPolicyController::class, 'index'])->name('achievement-based-salary-policy.index');
        Route::resource('achievement-based-salary-policy', AchievementBasedSalaryPolicyController::class);
        Route::resource('target', TargetController::class);
    });


});