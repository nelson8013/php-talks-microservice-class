<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;

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



Route::controller(UserController::class)->prefix('user')->group( function(){
    Route::get('health',       'health')->name('health');
    Route::get('user/{id}', 'user')->name('user');
    Route::get('users',     'users')->name('users');
    Route::post('create',    'addUser')->name('create');
    Route::put('update/{id}', 'updateUser')->name('update');
    Route::get('exists/{id}',  'doesUserExist')->name('exists');
});