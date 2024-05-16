<?php

use App\Api\Controllers\AdminUserController;
use App\Api\Controllers\BannerController;
use App\Api\Controllers\CouponCodeController;
use App\Api\Controllers\DemoControllerTest;
use App\Api\Controllers\EmailTemplateController;
use App\Api\Controllers\MediaController;
use App\Api\Controllers\OrderController;
use App\Api\Controllers\PaymentController;
use App\Api\Controllers\ProductBrandController;
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
use App\Api\Controllers\TempTemplateController;
use App\Api\Controllers\ExternalApiController;
use App\Api\Controllers\ProductAttributeController;
use App\Api\Controllers\ProductAttributeValueController;
use App\Api\Controllers\SliderImageController;

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

//customers without role id , auth old flow
Route::post('/customer/login', [UserController::class, 'login'])->name('login');
Route::post('/customer/signup', [UserController::class, 'signUp']);
Route::post('/customer/forget-password', [UserController::class, 'forgetPassword']);

//admin panel users with role id
Route::post('/admin/login', [AdminUserController::class, 'login'])->name('adminLogin');
Route::post('/admin/signup', [AdminUserController::class, 'signUp']);
Route::post('/admin/forget-password', [AdminUserController::class, 'forgetPassword']);

Route::post('product/list', [ProductController::class, 'list']);
Route::get('product/{id}/data', [ProductController::class, 'getProductById']);
Route::post('product/demo-form-upload', [ProductController::class, 'demoFormUpload']);


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

Route::get('product/get-max-price', [ProductController::class, 'getMaxPrice']);

Route::post('product/filter', [ProductController::class, 'getProducts']);

Route::post('product-brand/list', [ProductBrandController::class, 'list']);

Route::get('product/dashboard-list', [ProductController::class, 'dashboardProductList']);
Route::get('product/featured-list', [ProductController::class, 'getFeaturedProductsList']);


Route::post('coupon-code/validate', [CouponCodeController::class, 'validateCouponCode']);

Route::post('stripe-charge', [PaymentController::class, 'processStripePayment']);


Route::get('template/list', [TempTemplateController::class, 'list']);
Route::get('template/{id}/data', [TempTemplateController::class, 'getById']);

Route::post('shipping-calculation', [ExternalApiController::class, 'getShippingQuote']);

Route::get('product-attribute/{id}/get-by-id', [ProductAttributeController::class, 'getById']);

Route::get('customer/logout', [UserController::class, 'logout']);


Route::group(['middleware' => 'auth.jwt'], function () {

    Route::get('/get-admin-user', [AdminUserController::class, 'getUser']);

    Route::get('/get-customer', [UserController::class, 'getUser']);

    Route::get('/{id}/get-admin-user', [AdminUserController::class, 'getAdminById']);

    Route::get('/{id}/get-customer', [AdminUserController::class, 'getUserById']);

    Route::post('/customer/update', [UserController::class, 'updateUser']);

    Route::post('/role/save', [RolePermissionController::class, 'saveRole']);
    Route::post('/permission/save', [RolePermissionController::class, 'savePermission']);
    Route::post('/sync-role-permission', [RolePermissionController::class, 'synRolePermissions']);
    Route::get('/role/{roleId}/delete', [RolePermissionController::class, 'deleteRole']);
    Route::get('/permission/{permissionId}/delete', [RolePermissionController::class, 'deletePermission']);
    Route::get('/role-permission/list', [RolePermissionController::class, 'rolePermissionList']);
    Route::get('/role/{id}/data', [RolePermissionController::class, 'roleById']);
    Route::get('/permission/{id}/data', [RolePermissionController::class, 'permissionById']);

    Route::get('/role/list', [RolePermissionController::class, 'roleList']);
    Route::get('/permission/list', [RolePermissionController::class, 'permissionList']);
    Route::get('/{role}/permission-list', [RolePermissionController::class, 'permissionListForRole']);

    Route::get('/{userId}/order-status-update', [AdminUserController::class, 'getSentEmails']);

    //product routes
    Route::prefix('/product')->group(function () {

        Route::post('/save', [ProductController::class, 'upsert']);
        Route::delete('/{id}/delete', [ProductController::class, 'delete']);

        Route::post('/multiple-delete', [ProductController::class, 'multipleDelete']);
    });

    //category routes
    Route::prefix('/category')->group(function () {

        Route::post('/save', [ProductCategoryController::class, 'upsert']);
        Route::delete('/{id}/delete', [ProductCategoryController::class, 'delete']);
        Route::get('/list', [ProductCategoryController::class, 'list']);
        Route::get('/{id}/category-data', [ProductCategoryController::class, 'getProductCategoryById']);

        Route::post('/multiple-delete', [ProductCategoryController::class, 'multipleDelete']);
    });

    //customer route

    Route::prefix('/customer')->group(function () {

        Route::post('/list', [AdminUserController::class, 'list']);
    });

    //product brand routes

    Route::prefix('/product-brand')->group(function () {

        Route::post('/save', [ProductBrandController::class, 'upsert']);
        Route::delete('/{id}/delete', [ProductBrandController::class, 'delete']);

        Route::get('/{id}/data', [ProductBrandController::class, 'getProductBrandById']);

        Route::post('/multiple-delete', [ProductBrandController::class, 'multipleDelete']);
    });

    //coupon code
    Route::prefix('/coupon-code')->group(function () {

        Route::post('/save', [CouponCodeController::class, 'upsert']);
        Route::post('/list', [CouponCodeController::class, 'list']);

        Route::delete('/{id}/delete', [CouponCodeController::class, 'delete']);

        Route::get('/{id}/data', [CouponCodeController::class, 'getById']);

        Route::post('/multiple-delete', [CouponCodeController::class, 'multipleDelete']);
    });

    //order routes

    Route::prefix('/order')->group(function () {

        Route::post('/list', [OrderController::class, 'list']);
        Route::get('/{id}/data', [OrderController::class, 'getById']);
        Route::post('/update-status', [OrderController::class, 'updateStatus']);
    });

    //static page tempalates
    Route::prefix('/template')->group(function () {

        Route::post('/save', [TempTemplateController::class, 'upsert']);

        Route::post('/multiple-delete', [TempTemplateController::class, 'multipleDelete']);
        Route::delete('/{id}/delete', [TempTemplateController::class, 'delete']);
        Route::post('/image-upload', [TempTemplateController::class, 'imageUpload']);
    });

    //email templates

    Route::prefix('/email-template')->group(function () {

        Route::post('/save', [EmailTemplateController::class, 'upsert']);
        Route::get('/list', [EmailTemplateController::class, 'list']);
        Route::get('/{id}/data', [EmailTemplateController::class, 'getById']);
        Route::post('/multiple-delete', [EmailTemplateController::class, 'multipleDelete']);
        Route::delete('/{id}/delete', [EmailTemplateController::class, 'delete']);
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

        Route::post('/update', [UserController::class, 'update']);

        Route::get('/{id}/delete', [AdminUserController::class, 'delete']);
        Route::get('/list', [AdminUserController::class, 'list']);
        Route::get('/{id}/orders', [AdminUserController::class, 'getOrders']);
    });

    //product attribute Routes 

    Route::prefix('/product-attribute')->group(function () {

        Route::post('/upsert', [ProductAttributeController::class, 'upsert']);

        Route::get('/{id}/delete', [ProductAttributeController::class, 'delete']);
        Route::post('/list', [ProductAttributeController::class, 'list']);
        Route::get('/{id}/values', [ProductAttributeController::class, 'getValues']);
        Route::post('/multiple-delete', [ProductAttributeController::class, 'multipleDelete']);
    });

    //product attribute Value Routes 

    Route::prefix('/product-attribute-value')->group(function () {

        Route::post('/upsert', [ProductAttributeValueController::class, 'upsert']);
        Route::get('/{id}/delete', [ProductAttributeValueController::class, 'delete']);
        Route::get('/{id}/get-by-id', [ProductAttributeValueController::class, 'getById']);
        Route::post('/multiple-delete', [ProductAttributeValueController::class, 'multipleDelete']);

    });

    //media uploades (left banner and right banner)

    Route::prefix('/banner')->group(function () {

        // in edit of banner case
        // it will simply gets updated (will overrite the last one) 

        Route::post('/upload', [BannerController::class, 'upload']);
        Route::get('/{id}/delete', [BannerController::class, 'delete']);
        Route::get('/list', [BannerController::class, 'list']);

    });

    //slider image uploades 

    Route::prefix('/slider-image')->group(function () {

        Route::post('/upload', [SliderImageController::class, 'upload']);
        Route::get('/{id}/delete', [SliderImageController::class, 'delete']);
        Route::get('/list', [SliderImageController::class, 'list']);
        Route::post('/multiple-delete', [SliderImageController::class, 'multipleDelete']);

    });


});

Route::post('/send-mail', [DemoControllerTest::class, 'sendMail']);
Route::post('/send-notification', [DemoControllerTest::class, 'sendMail']);
