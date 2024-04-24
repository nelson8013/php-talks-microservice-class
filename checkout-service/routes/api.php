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




Route::controller(CartController::class)->prefix('cart')->group( function(){
    Route::post('add',  'addToCart')->name('add');
    Route::get('carts', 'carts')->name('carts');
    Route::get('{id}',  'cart')->name('cart');
});

// $router->group(['prefix' => 'api', 'middleware' => ['client.credentials']], function () use ($router) {
//     $router->group(['prefix' => 'product'], function () use ($router) {
//         $router->get('/', ['uses' => 'ProductController@index']);
//         $router->post('/', ['uses' => 'ProductController@store']);
//         $router->get('/{product}', ['uses' => 'ProductController@show']);
//         $router->patch('/{product}', ['uses' => 'ProductController@update']);
//         $router->delete('/{product}', ['uses' => 'ProductController@destroy']);
//     });
//     $router->group(['prefix' => 'order'], function () use ($router) {
//         $router->get('/', ['uses' => 'OrderController@index']);
//         $router->post('/', ['uses' => 'OrderController@store']);
//         $router->get('/{order}', ['uses' => 'OrderController@show']);
//         $router->patch('/{order}', ['uses' => 'OrderController@update']);
//         $router->delete('/{order}', ['uses' => 'OrderController@destroy']);
//     });
// });