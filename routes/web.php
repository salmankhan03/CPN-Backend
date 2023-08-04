<?php

use App\Http\Controllers\ResetPasswordController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    return view('welcome');
});

Route::get('password/reset', [ResetPasswordController::class, 'editPassword']);
Route::put('password/reset/{email}', [ResetPasswordController::class, 'update'])->name('password.update');
