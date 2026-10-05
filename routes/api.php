<?php

use App\Http\Controllers\Api\v1\AuctionController;
use App\Http\Controllers\Api\v1\Auth\CustomerAuthController;
use App\Http\Controllers\Api\v1\ManufactureController;
use App\Http\Controllers\Api\v1\ModelController;
use App\Http\Controllers\Api\v1\VehicleController;
use App\Http\Controllers\Api\v1\VehicleTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function() {
    Route::group(['prefix' => '/auth/customer'], function () {
        Route::post('/login', [CustomerAuthController::class, 'login']);/**/
        Route::post('/login/mobile', [CustomerAuthController::class, 'loginWithMobile']);/**/
        Route::post('/login/social/google', [CustomerAuthController::class, 'loginWithGoogle']);
        Route::post('/login/social/facebook', [CustomerAuthController::class, 'loginWithFacebook']);
        Route::post('/login/social/apple', [CustomerAuthController::class, 'loginWithApple']);
        Route::post('/login/social/sms', [CustomerAuthController::class, 'loginWithSms']);

        Route::post('/register', [CustomerAuthController::class, 'register']);
        Route::post('/register/mobile', [CustomerAuthController::class, 'registerWithMobile']);
        Route::post('/register/social/{provider}', [CustomerAuthController::class, 'registerWithSocial']);

        Route::get('/login/{provider}/callback', [CustomerAuthController::class, 'handleProviderCallback']);

        Route::post('/password/reset', [CustomerAuthController::class, 'resetPassword']);

        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::post('/password/update', [CustomerAuthController::class, 'updatePassword']);

            Route::get('/verify-status', [CustomerAuthController::class, 'verifyStatus'])->middleware(['api.verified', 'api.password.reset']);/**/
            Route::post('/verify/{type?}', [CustomerAuthController::class, 'verifyLogin']);/**/
            Route::post('/verify/resend/otp', [CustomerAuthController::class, 'resendOtp']);/**/

            Route::post('/logout', [CustomerAuthController::class, 'logout']);/**/
            Route::post('/delete', [CustomerAuthController::class, 'deleteAccount']);/**/

            Route::get('/me', [CustomerAuthController::class, 'details']);
            Route::post('/update', [CustomerAuthController::class, 'updateDetails']);
            Route::post('/update-avatar', [CustomerAuthController::class, 'updateAvatar']);
            Route::post('/avatar-image-upload', [CustomerAuthController::class, 'uploadAvatar']);
            Route::put('/firebase-token', [CustomerAuthController::class, 'firebaseToken']);


        });
    });
    
    Route::get('local-stock', [VehicleController::class, 'index']);
    Route::get('local-stock/{id}', [VehicleController::class, 'details']);
    Route::get('local-models', [ModelController::class, 'index']);
    Route::get('local-manufactures', [ManufactureController::class, 'index']);
    Route::get('local-types', [VehicleTypeController::class, 'index']);

    Route::get('auction-list', [AuctionController::class, 'index']);
    Route::get('auction-list/{id}', [AuctionController::class, 'details']);
    Route::get('auction-manufactures', [AuctionController::class, 'manufactures']);
    Route::get('auction-models', [AuctionController::class, 'models']);
    Route::get('auction-years', [AuctionController::class, 'years']);
    Route::get('auction-chassisids', [AuctionController::class, 'chassisids']);
    Route::get('auction-capacity', [AuctionController::class, 'capacity']);
    Route::get('auction-dates', [AuctionController::class, 'dates']);
});
 
