<?php

use App\Http\Controllers\Dashboard\MainController;
use App\Http\Controllers\Dashboard\StaffUserAuthController;
use App\Http\Controllers\Dashboard\UserController;
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



Route::group(['prefix' => LaravelLocalization::setLocale()], function () {
    //-----------------------------------Auth Routes ----------------------------------------------------------------------
    Route::view('/login', 'auth.login')->name('login.view');
    Route::post('/login', [StaffUserAuthController::class, 'login'])->name('login');
    Route::post('/logout', [StaffUserAuthController::class, 'logout'])->name('logout');

    //--------------------------------Dashboard Routes -------------------------------------------------------------
    Route::group(['middleware' => 'auth:staff_users'], function () {
        Route::get('/', [MainController::class, 'index'])->name('dashboard.index');

        //-------------------------- manage users routes -----------------------------
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [UserController::class, 'index'])
            ->name('users.index')
            ->middleware('permission:view-users');
            Route::get('/create', [UserController::class, 'create'])
            ->name('users.create')
            ->middleware('permission:add-users');
            Route::post('/store', [UserController::class, 'store'])
            ->name('users.store')
            ->middleware('permission:add-users');
        });
    });
});
