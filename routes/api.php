<?php

use App\Http\Controllers\Public\AccountController;
use App\Http\Controllers\Public\AuthController;
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
});

Route::get('/test', function () {
    return 'hi';
})->middleware('user_authentication');
