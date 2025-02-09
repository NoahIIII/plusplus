<?php

use App\Http\Controllers\Public\SectionController;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Public\AccountController;
use App\Http\Controllers\Public\AuthController;
use App\Http\Controllers\Public\BusinessTypeController;
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

//----------------------------------- Auth Routes ------------------------------------------------------
Route::group(['prefix' => 'auth'], function () {
    Route::post('/signin', [AuthController::class, 'sendOTP']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOTP']);
});

//----------------------------------- User Account Routes ------------------------------------------------------
Route::group(['prefix' => 'account', 'middleware' => 'user_authentication'], function () {
    Route::patch('/update', [AccountController::class, 'updateAccountDetails']);
    Route::patch('/update-business-type', [AccountController::class, 'updateBusinessType']);
});

//------------------------------------ Business Types Routes ------------------------------------------------------
Route::group(['prefix' => '/business-types'], function () {
    Route::get('/', [BusinessTypeController::class, 'getBusinessTypes']);
});

//------------------------------------ Categories Routes ------------------------------------------------------
Route::group(['prefix' => '/categories', 'middleware' => 'user_authentication'], function () {
    Route::get('/', [CategoryController::class, 'getMainCategories']);
    Route::get('/children/{parentId}', [CategoryController::class, 'getChildCategories']);
});

//------------------------------------Section Routes ------------------------------------------------------
Route::group(['prefix'=>'sections', 'middleware' => 'user_authentication'], function () {
    Route::get('/', [SectionController::class, 'getAllSections']);
    Route::get('products/{sectionId}', [SectionController::class, 'getSectionProducts']);
});

Route::get('/test', function () {
    return 'hi';
})->middleware('user_authentication');
