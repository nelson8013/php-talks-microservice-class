<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\CartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/




Route::controller(CartController::class)->prefix('checkout')->group( function(){
    Route::get('health',  'health')->name('health');
    Route::post('add',  'addToCart')->name('add');
    Route::get('carts', 'carts')->name('carts');
    Route::get('{id}',  'cart')->name('cart');
});
