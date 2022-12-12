<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Customer\CustomerAuthController;
use App\Http\Controllers\API\Landlone\LandloneAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('landlone')->group( function () {
    Route::post('register', [LandloneAuthController::class ,'register']);
    Route::post('login', [LandloneAuthController::class ,'login']);

    Route::middleware('auth:api_l_users')->group( function (){

        Route::post('logout', [LandloneAuthController::class ,'logout']);

    });
});

Route::prefix('customer')->group( function () {
    Route::post('register', [CustomerAuthController::class ,'register']);
    Route::post('login', [CustomerAuthController::class ,'login']);

    Route::middleware('auth:api_c_users')->group( function (){

        Route::post('logout', [CustomerAuthController::class ,'logout']);

    });
});
