<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth', 'before' => 'csrf'], function ($router) {
    Route::post('role/menu-list/save', [AdminController::class, 'saveMenuList']);
});
