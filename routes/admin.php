<?php

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CampaingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageSettingController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\Api\V1\CustomerAuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/get-booking-data', [DashboardController::class, 'getBookingDataForChart'])->name('get.booking.data');

    Route::group(['prefix' => 'admin', 'as' => 'admin', 'controller' => ProfileController::class], function () {
        Route::post('profile/update', 'update')->name('profile-update');
        Route::get('profile', 'show')->name('profile-show');
    });

    //Zone Routes
    Route::group(['controller' => ZoneController::class], function () {
        Route::get('/add-zone', 'addZone')->name('add.zone');
        Route::get('/list-zone', 'zonelist')->name('list.zone');
        Route::post('/store/zone', 'store')->name('zone.store');
        Route::delete('/zones/delete/{id}', 'destroy')->name('zone.destroy');
        Route::get('/zones/{id}/edit', 'zoneEdit')->name('zones.edit');
        Route::put('/zones/{zone}', 'update')->name('zones.update');
    });

    // Category routes
    Route::group(['prefix' => 'category', 'controller' => CategoryController::class], function () {
        Route::get('/add-category', 'categoryAdd')->name('add.category');
        Route::get('/list-category', 'categoryList')->name('list.category');
        Route::post('/store-categories', 'store')->name('categories.store');
        Route::delete('/delete/{id}', 'destroy')->name('categories.destroy');
        Route::get('/edit/{id}', 'edit')->name('category.edit');
        Route::put('/update/{id}', 'update')->name('category.update');
    });

    //admin Routes
    Route::group(['prefix' => 'admin', 'controller' => ProviderController::class], function () {
        Route::get('/profile', 'show')->name('profile-show');
        Route::post('/profile/update', 'update')->name('profile-update');
    });

    // ajex
    Route::get('/api/zones/{zone}/categories', [ServiceController::class, 'getCategoriesByZone']);
    Route::get('/api/zones/{zone}/services', [ServiceController::class, 'getServicesByZone']);
    Route::get('/api/categories/{category}/subcategories', [CategoryController::class, 'getSubcategories']);
    Route::get('/api/zones/{zone}/providers', [ServiceController::class, 'getProvidersByZone']);
    //Category

    //Sub Category
    Route::group(['prefix' => 'sub-category', 'controller' => SubCategoryController::class], function () {
        Route::get('/add-subcategory', 'subcategoryAdd')->name('add.subcategory');
        Route::get('/list-subcategory', 'subcategoryList')->name('list.subcategory');
        Route::post('/store-subcategories', 'subCategoryStore')->name('subcategories.store');
        Route::delete('/delete/{id}', 'destroy')->name('subcategories.destroy');
        Route::get('/edit/{id}', 'edit')->name('subcategory.edit');
        Route::put('/update/{id}', 'update')->name('subcategory.update');
    });

    //service
    Route::group(['prefix' => 'service', 'controller' => ServiceController::class], function () {
        Route::get('/add-service', [ServiceController::class, 'serviceAdd'])->name('add.service');
        Route::get('/list-service', [ServiceController::class, 'list'])->name('list.service');
        Route::post('/store-service', [ServiceController::class, 'Store'])->name('service.store');
        Route::delete('/delete/{id}', [ServiceController::class, 'destroy'])->name('service.destroy');
        Route::get('/edit/{id}', [ServiceController::class, 'serviceEidt'])->name('service.edit');
        Route::put('/update/{id}', [ServiceController::class, 'update'])->name('service.update');
        Route::delete('/delete/{id}', [ServiceController::class, 'destroy'])->name('service.destroy');
        Route::get('/details/{id}', [ServiceController::class, 'details'])->name('service.details');
    });


    Route::get('/user/list', [CustomerController::class, 'userList'])->name('user.list');

    Route::get('/campaign/add', [CampaingController::class, 'add'])->name('new.campaign.add');
    Route::get('/service/campaign/add', [CampaingController::class, 'serviceCampaignAdd'])->name('service.campaign.add');
    Route::get('/campaign/list', [CampaingController::class, 'list'])->name('campaign.list');

    Route::post('/campaign/store', [CampaingController::class, 'Store'])->name('campaign.store');
    Route::post('service/campaign/store', [CampaingController::class, 'serviceStore'])->name('service.campaign.store');
    Route::get('/campaign/list', [CampaingController::class, 'campaignlist'])->name('campaign.list');
    Route::delete('/campaign/delete/{id}', [CampaingController::class, 'destroy'])->name('campaign.destroy');

    Route::get('/coupon/add', [CampaingController::class, 'addCoupon'])->name('add-coupon');
    Route::get('/coupon/list', [CampaingController::class, 'listCoupon'])->name('coupon-list');
    Route::post('/coupon/store', [CampaingController::class, 'store'])->name('store-coupon');
    Route::delete('/coupons/{coupon}', [CampaingController::class, 'destroy'])->name('delete-coupon');
    Route::put('/coupon/update/{coupon}', [CampaingController::class, 'update'])->name('coupon-update');
    Route::get('/coupon/edit/{id}', [CampaingController::class, 'edit'])->name('coupon-edit');
    Route::get('/payment/list/{slug}', [PaymentController::class, 'paymentList'])->name('payment-list');

    Route::get('/booking/list/{statusSlug}', [BookingController::class, 'bookingList'])->name('booking.list');
    Route::get('/booking/details/{id}', [BookingController::class, 'details'])->name('booking.details');
    Route::get('/invoice/{booking}', [BookingController::class, 'invoice'])->name('invoice');
    Route::get('/download_invoice/{booking}', [BookingController::class, 'downloadPDF'])->name('download-invoice');

    Route::get('/user/list', [CustomerController::class, 'userList'])->name('user.list');

    Route::get('/page/setup/{slug}', [PageSettingController::class, 'index'])->name('page-setting');
    Route::post('/page-settings/store', [PageSettingController::class, 'store'])->name('page-settings.store');
});

require __DIR__ . '/auth.php';
