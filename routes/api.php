<?php

use App\Api\Controllers\ProductController;
use App\Api\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [UserController::class, 'index']);
Route::get('/index2', [UserController::class, 'index2']);
Route::get('/unauthorized', [UserController::class, 'unauthorized'])->name('unauthorized');


Route::post('/login', [UserController::class, 'login']);
Route::post('/signup', [UserController::class, 'signUp']);
Route::post('/forget-password', [UserController::class, 'forgetPassword']);


Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('/get-user', [UserController::class, 'getUser']);

    //product routes
    Route::post('/product/save', [ProductController::class, 'createProduct']);
});
