<?php

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

// user with specific middleware
// Route::get('/profile', function () {
//     // ...
// })->middleware(Authenticate::class);
Route::get('/', [UserController::class, 'index']);
Route::get('/index2', [UserController::class, 'index']);

Route::post('/login', [UserController::class, 'login']);
Route::post('/signup', [UserController::class, 'signUp']);



Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::post('/send-mail', function () {
Route::post('/forget-password', [UserController::class, 'forgetPassword']);
// });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
