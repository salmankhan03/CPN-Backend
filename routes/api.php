<?php

use App\Api\Controllers\ProductController;
use App\Api\Controllers\RolePermissionController;
use App\Api\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;




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


Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/signup', [UserController::class, 'signUp']);
Route::post('/forget-password', [UserController::class, 'forgetPassword']);

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');



Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return 'Verification Successful';
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('/get-user', [UserController::class, 'getUser']);

    Route::get('/role/save', [RolePermissionController::class, 'saveRole']);
    Route::get('/permission/save', [RolePermissionController::class, 'savePermission']);
    Route::get('/sync-role-permission', [RolePermissionController::class, 'synRolePermissions']);
    Route::get('/role/delete', [RolePermissionController::class, 'deleteRole']);
    Route::get('/permission/delete', [RolePermissionController::class, 'deletePermission']);
    Route::get('/role-permission/list', [RolePermissionController::class, 'rolePermissionList']);

    //product routes
    Route::post('/product/save', [ProductController::class, 'createProduct']);
});
