<?php

use App\Http\Controllers\Dashboard\BrandController;
use App\Http\Controllers\Dashboard\CategoryController;
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
        Route::group(['prefix' => 'users', 'middleware' => 'permission:manage-users'], function () {
            Route::get('/', [UserController::class, 'index'])
                ->name('users.index');
            Route::get('/create', [UserController::class, 'create'])
                ->name('users.create');
            Route::post('/store', [UserController::class, 'store'])
                ->name('users.store');
            Route::get('/edit/{userId}', [UserController::class, 'edit'])
                ->name('users.edit');
            Route::get('/{userId}', [UserController::class, 'show'])
                ->name('users.show');
            Route::put('/update/{user}', [UserController::class, 'update'])
                ->name('users.update');
            Route::delete('/destroy/{user}', [UserController::class, 'destroy'])
                ->name('users.destroy');
        });

        //------------------------- Manage Staff Users Routes -----------------------------
        Route::group(['prefix' => 'admins', 'middleware' => 'permission:manage-staff-users'], function () {
            Route::get('/', [StaffUserController::class, 'index'])
                ->name('admins.index');
            Route::get('/create', [StaffUserController::class, 'create'])
                ->name('admins.create');
            Route::post('/store', [StaffUserController::class, 'store'])
                ->name('admins.store');
            Route::get('/edit/{staffUserId}', [StaffUserController::class, 'edit'])
                ->name('admins.edit');
            Route::get('/{staffUserId}', [StaffUserController::class, 'show'])
                ->name('admins.show');
            Route::put('/update/{staffUser}', [StaffUserController::class, 'update'])
                ->name('admins.update');
            Route::delete('/destroy/{staffUser}', [StaffUserController::class, 'destroy'])
                ->name('admins.destroy');
        });

        //--------------------------------- Manage Brands Routes -----------------------------
        Route::group(['prefix' => 'brands', 'middleware' => 'permission:manage-brands'], function () {
            Route::get('/type/{slug}', [BrandController::class, 'index'])
                ->name('brands.index');
            Route::get('/create', [BrandController::class, 'create'])
                ->name('brands.create');
            Route::post('/store', [BrandController::class, 'store'])
                ->name('brands.store');
            Route::get('/edit/{brandId}', [BrandController::class, 'edit'])
                ->name('brands.edit');
            Route::get('/{brandId}', [BrandController::class, 'show'])
                ->name('brands.show');
            Route::put('/update/{brand}', [BrandController::class, 'update'])
                ->name('brands.update');
            Route::delete('/destroy/{brand}', [BrandController::class, 'destroy'])
                ->name('brands.destroy');
        });

        //--------------------------------- Manage Categories Routes -----------------------------
        Route::group(['prefix' => 'categories', 'middleware' => 'permission:manage-categories'], function () {
            Route::get('/type/{slug}', [CategoryController::class, 'index'])
                ->name('categories.index');
            Route::get('/create', [CategoryController::class, 'create'])
                ->name('categories.create');
            Route::post('/store', [CategoryController::class, 'store'])
                ->name('categories.store');
            Route::get('/edit/{categoryId}', [CategoryController::class, 'edit'])
                ->name('categories.edit');
            // Route::get('/{categoryId}', [CategoryController::class, 'show'])
            // ->name('categories.show')->middleware('permission:view-categories');
            Route::put('/update/{category}', [CategoryController::class, 'update'])
                ->name('categories.update');
            Route::delete('/destroy/{category}', [CategoryController::class, 'destroy'])
                ->name('categories.destroy');
            Route::get('/get/by-level', [CategoryController::class, 'getCategoriesByLevel']);
        });
    });
});
