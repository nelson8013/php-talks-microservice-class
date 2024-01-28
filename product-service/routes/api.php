<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ProductController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::controller(ProductController::class)->prefix('product')->group( function(){
    Route::get('product/{id}', 'product')->name('product');
    Route::get('products',     'products')->name('products');
    Route::post('add',         'addProduct')->name('add');
    Route::get('exists/{id}',  'doesProductExist')->name('exists');
    Route::get('price/{id}',   'productPrice')->name('price');
});