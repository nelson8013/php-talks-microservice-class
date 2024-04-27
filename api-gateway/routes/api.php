<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ServiceRegistryController;
use App\Http\Controllers\api\RoutingController;







Route::controller(ServiceRegistryController::class)->prefix('gate')->group( function(){
    Route::get('health',  'health')->name('health');
    Route::get('check',                 'check')->name('check');
    Route::get('services',              'services')->name('services');
    Route::post('register',             'register')->name('register');
    Route::get('resolve/{serviceName}', 'resolve')->name('resolve');
    Route::get('flush', 'flush')->name('flush');
});


    Route::prefix('product')->group(function () {
        Route::any('{any}', [RoutingController::class, 'route'])->where('any', '.*');
    });

    Route::prefix('inventory')->group(function () {
        Route::any('{any}', [RoutingController::class, 'route'])->where('any', '.*');
    });

    Route::prefix('checkout')->group(function () {
        Route::any('{any}', [RoutingController::class, 'route'])->where('any', '.*');
    });

