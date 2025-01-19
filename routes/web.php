<?php

use App\Http\Controllers\Dashboard\BrandController;
use App\Http\Controllers\Dashboard\MainController;
use App\Http\Controllers\Dashboard\StaffUserAuthController;
use App\Http\Controllers\Dashboard\StaffUserController;
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

        //-------------------------- Manage Users Routes -----------------------------
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [UserController::class, 'index'])
            ->name('users.index')->middleware('permission:view-users');
            Route::get('/create', [UserController::class, 'create'])
            ->name('users.create')->middleware('permission:add-users');
            Route::post('/store', [UserController::class, 'store'])
            ->name('users.store')->middleware('permission:add-users');
            Route::get('/edit/{userId}', [UserController::class, 'edit'])
            ->name('users.edit')->middleware('permission:edit-users');
            Route::get('/{userId}', [UserController::class, 'show'])
            ->name('users.show')->middleware('permission:view-users');
            Route::put('/update/{user}', [UserController::class, 'update'])
            ->name('users.update')-> middleware('permission:edit-users');
            Route::delete('/destroy/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy')-> middleware('permission:delete-users');
        });

        //------------------------- Manage Staff Users Routes -----------------------------
        Route::group(['prefix' => 'admins'], function () {
            Route::get('/', [StaffUserController::class, 'index'])
            ->name('admins.index')->middleware('permission:view-staff-users');
            Route::get('/create', [StaffUserController::class, 'create'])
            ->name('admins.create')->middleware('permission:add-staff-users');
            Route::post('/store', [StaffUserController::class, 'store'])
            ->name('admins.store')->middleware('permission:add-staff-users');
            Route::get('/edit/{staffUserId}', [StaffUserController::class, 'edit'])
            ->name('admins.edit')->middleware('permission:edit-staff-users');
            Route::get('/{staffUserId}', [StaffUserController::class, 'show'])
            ->name('admins.show')->middleware('permission:view-staff-users');
            Route::put('/update/{staffUser}', [StaffUserController::class, 'update'])
            ->name('admins.update')-> middleware('permission:edit-staff-users');
            Route::delete('/destroy/{staffUser}', [StaffUserController::class, 'destroy'])
            ->name('admins.destroy')-> middleware('permission:delete-staff-users');
        });

        //--------------------------------- Manage Brands Routes -----------------------------
        Route::group(['prefix' => 'brands'], function () {
            Route::get('/type/{slug}', [BrandController::class, 'index'])
            ->name('brands.index')->middleware('permission:view-brands');
            Route::get('/create', [BrandController::class, 'create'])
            ->name('brands.create')->middleware('permission:add-brands');
            Route::post('/store', [BrandController::class, 'store'])
            ->name('brands.store')->middleware('permission:add-brands');
            Route::get('/edit/{brandId}', [BrandController::class, 'edit'])
            ->name('brands.edit')->middleware('permission:edit-brands');
            Route::get('/{brandId}', [BrandController::class, 'show'])
            ->name('brands.show')->middleware('permission:view-brands');
            Route::put('/update/{brand}', [BrandController::class, 'update'])
            ->name('brands.update')-> middleware('permission:edit-brands');
            Route::delete('/destroy/{brand}', [BrandController::class, 'destroy'])
            ->name('brands.destroy')-> middleware('permission:delete-brands');
        });
    });
});
