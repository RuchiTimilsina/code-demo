<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(
    [
        'namespace'=>'App\Http\Controllers\Api\V1\Auth'
    ],
      function(){
        Route::post('/auth/register', 'AuthController@postRegister');

    }
);

Route::group(
    [
        'namespace'=>'App\Http\Controllers\Api\V1'
    ],
      function(){
        Route::post('/subscription/inAppPurchaseVerification', 'SubscriptionController@inAppPurchaseVerification');
        Route::get('/subscription/getSubscriptionStatus', 'SubscriptionController@getSubscriptionStatus');
        Route::get('/subscription/getReport', 'SubscriptionController@getReport');

    }
);
