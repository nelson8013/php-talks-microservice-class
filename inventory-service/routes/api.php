<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\InventoryController;


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


Route::controller(InventoryController::class)->prefix('inventory')->group( function(){
    Route::get('inventory/{id}',       'inventory')->name('inventory');
    Route::get('inventories',          'inventories')->name('inventories');
    Route::post('add',                 'addInventory')->name('add');
    Route::put('update',               'updateProductQuantity')->name('update');
    Route::get('quantity/{productId}', 'getProductQuantity')->name('quantity');
    Route::get('subtract-quantity/{productId}/{quantityToSubtract}', 'subtractProductQuantity')->name('subtract-quantity');
});
