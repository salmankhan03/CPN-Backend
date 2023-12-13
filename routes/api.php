<?php

use App\Api\Controllers\MediaController;
use App\Api\Controllers\OrderController;
use App\Api\Controllers\ProductController;
use App\Api\Controllers\RolePermissionController;
use App\Api\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Api\Controllers\ProductCategoryController;
use App\Api\Controllers\ProductSubCategoryController;


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
Route::post('product/list', [ProductController::class, 'list']);
Route::get('product/{id}/data', [ProductController::class, 'getProductById']);

Route::post('category/tree', [ProductCategoryController::class, 'getCategoryTree']);

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

Route::prefix('/cart')->group(function () {

    Route::post('/place-order', [OrderController::class, 'placeOrder']);
});


Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('/get-user', [UserController::class, 'getUser']);

    Route::get('/role/save', [RolePermissionController::class, 'saveRole']);
    Route::get('/permission/save', [RolePermissionController::class, 'savePermission']);
    Route::get('/sync-role-permission', [RolePermissionController::class, 'synRolePermissions']);
    Route::get('/role/delete', [RolePermissionController::class, 'deleteRole']);
    Route::get('/permission/delete', [RolePermissionController::class, 'deletePermission']);
    Route::get('/role-permission/list', [RolePermissionController::class, 'rolePermissionList']);

    //product routes
    Route::prefix('/product')->group(function () {

        Route::post('/save', [ProductController::class, 'upsert']);
        Route::delete('/{id}/delete', [ProductController::class, 'delete']);


        Route::post('/multiple_delete', [ProductController::class, 'multipleDelete']);
    });

    //category routes
    Route::prefix('/category')->group(function () {

        Route::post('/save', [ProductCategoryController::class, 'upsert']);
        Route::delete('/{id}/delete', [ProductCategoryController::class, 'delete']);
        Route::get('/list', [ProductCategoryController::class, 'list']);
        Route::get('/{id}/category-data', [ProductCategoryController::class, 'getProductCategoryById']);

        Route::post('/multiple_delete', [ProductCategoryController::class, 'multipleDelete']);
    });


    Route::post('/image/upload', [MediaController::class, 'upload']);
    Route::post('/image/multiple-delete', [MediaController::class, 'delete']);

    Route::get('/category/images', [MediaController::class, 'categoryImages']);
    Route::get('/product/images', [MediaController::class, 'productImages']);


    //sub category routes
    // Route::prefix('/sub-category')->group(function () {

    //     Route::post('/save', [ProductSubCategoryController::class, 'upsert']);
    //     Route::delete('/{id}/delete', [ProductSubCategoryController::class, 'delete']);
    //     Route::get('/list', [ProductSubCategoryController::class, 'list']);
    // });

    //user Routes
    Route::prefix('/user')->group(function () {

        Route::delete('/{id}/delete', [UserController::class, 'delete']);
        Route::get('/list', [UserController::class, 'list']);
    });
});
