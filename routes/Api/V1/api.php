<?php

use App\Http\Controllers\Api\V1\Auth\ProviderAuthController;
use App\Http\Controllers\Api\V1\BettingController;
use App\Http\Controllers\Api\V1\CampaignController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CateogryController;
use App\Http\Controllers\Api\V1\CustomerAuthController;
use App\Http\Controllers\Api\V1\ProviderController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\ZoneController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('send-otp', [CustomerAuthController::class,'sendOTP']);

Route::group(['prefix' => 'customer', 'as' => 'customer.', 'controller' => CustomerAuthController::class], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', 'login')->name('login');
        Route::post('/register', 'register')->name('register');
    });

    Route::middleware(['customer'])->group(function () {
        Route::get('/', 'customer');
        Route::post('/logout', 'logout')->name('logout');
        Route::post('/update', 'update')->name('update');
        Route::post('/delete', 'delete')->name('delete');
        Route::post('change-password', 'changePassword')->name('changePassword');
    });
});

// Routes for provider authentication
Route::group(['prefix' => 'auth/provider', 'as' => 'provider.', 'controller' => ProviderAuthController::class], function () {
    // guest route
    Route::post('/login', 'login')->name('login');
    Route::post('/register', 'register')->name('register');

    // authorization route
    Route::middleware(['provider'])->group(function () {
        Route::get('/', 'provider');
        Route::post('/logout', 'logout')->name('logout');
        Route::post('delete', 'delete')->name('delete');
        Route::post('update', 'update')->name('update');
        Route::post('update-password', 'changePassword')->name('changePassword');
    });
});

Route::middleware('customer')->group(function(){
    Route::post('/add_review',[ReviewController::class,'addReview']);
    Route::get('/provider_details/{id}',[ProviderController::class,'getProviderDetails']);
    Route::get('betting_list',[BettingController::class,'bettingList']);
    Route::post('betting-accept/{betting}',[BettingController::class,'accept']);
});

Route::group(['prefix' => 'provider','middleware' => 'provider','controller' => ProviderController::class], function (){
    Route::get('get-service','getService');
    Route::post('add-cart',[CartController::class,'addToWishList']);
    Route::post('delete-cart/{id}',[CartController::class,'removeFromWishList']);
    Route::get('get-carts',[CartController::class,'getWishList']);
    Route::post('service_betting',[BettingController::class,'store']);
});

Route::get('service/details/{id}',[ServiceController::class,'index']);
Route::get('service/search',[ServiceController::class,'searchServices']);

Route::group(['prefix' => 'customer/service','middleware' => 'customer'],function (){
    Route::post('add',[ServiceController::class,'createService']);
    Route::post('delete/{service}',[ServiceController::class,'delete']);
    Route::get('get',[ServiceController::class,'get']);
    Route::get('details/{id}',[ServiceController::class,'index']);
    Route::post('update/{service}',[ServiceController::class,'update']);
    Route::post('image/delete/{id}',[ServiceController::class,'imageDelete']);

});


// Categories routes
Route::group(["as" => "categories.", "controller" => CateogryController::class], function () {
    Route::get('categories','index');
    Route::get('/subcategories/{category}', 'SubCategory');
});

Route::group(["as" => "zone.", "controller" => ZoneController::class], function () {
    Route::get('/zone', 'getZone');
    Route::get('/zones', 'zones');
});

Route::group(['prefix' => 'campaign','controller' => CampaignController::class],function (){
    Route::get('list','index');
});
