<?php

use Illuminate\Support\Facades\Route;
use Modules\LocationManager\Controllers\AreaController;
use Modules\LocationManager\Controllers\DistrictController;
use Modules\LocationManager\Controllers\DivisionController;
use Modules\LocationManager\Controllers\LocationController;
use Modules\LocationManager\Controllers\LocationTypeController;
use Modules\LocationManager\Controllers\ThanaController;

Route::group(['middleware'=>'auth', 'prefix' => 'location-manager', 'as' => 'location_manager.'],function () {

        /* locations */
        Route::resource('locations', LocationController::class);

        /* locations Type */
        Route::resource('location-types', LocationTypeController::class);

        Route::resource('areas', AreaController::class);
        Route::resource('divisions', DivisionController::class);
        Route::resource('districts', DistrictController::class);
        Route::resource('thanas', ThanaController::class);
       

 
});