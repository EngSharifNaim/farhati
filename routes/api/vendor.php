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
Route::group([
    'prefix' => 'vendor'
],function (){
    Route::post('/login','App\Http\Controllers\Vendor\LoginController@login');
    Route::post('/register','App\Http\Controllers\Vendor\UserController@register');
    Route::post('/forgetPassword','App\Http\Controllers\Vendor\UserController@forgetPassword');
    Route::post('/resetPassword','App\Http\Controllers\Vendor\UserController@resetPassword');
    Route::post('/verify_mobile','App\Http\Controllers\Vendor\UserController@verify_mobile');
    Route::post('/send_otp','App\Http\Controllers\Vendor\UserController@send_otp');
    Route::post('/search','App\Http\Controllers\Vendor\UserController@search');

    Route::post('/get_all_vendors','App\Http\Controllers\User\VendorsController@get_all_vendors');
    Route::post('/get_vendor_by_category','App\Http\Controllers\User\VendorsController@get_vendor_by_category');
    Route::post('/get_vendor_by_location','App\Http\Controllers\User\VendorsController@get_vendor_by_location');
    Route::post('/get_vendor_by_id','App\Http\Controllers\User\VendorsController@get_vendor_by_id');
    Route::post('/get_vendor_works','App\Http\Controllers\User\VendorsController@get_vendor_works');
    Route::post('/get_gallery_by_vendor','App\Http\Controllers\User\VendorsController@get_gallery_by_vendor');

    Route::post('/get_product_by_category','App\Http\Controllers\User\ProductsController@get_product_by_category');
    Route::post('/get_product_by_vendor','App\Http\Controllers\User\ProductsController@get_product_by_vendor');


    Route::group(['middleware' => 'auth:api'],function (){
        Route::post('/home','App\Http\Controllers\Vendor\UserController@home');

        Route::post('/set_profile','App\Http\Controllers\Vendor\VendorsController@set_profile');
        Route::post('/get_profile','App\Http\Controllers\Vendor\VendorsController@get_profile');

        Route::post('/add_service','App\Http\Controllers\Vendor\VendorsController@add_service');
        Route::post('/get_services','App\Http\Controllers\Vendor\VendorsController@get_services');
        Route::post('/my_booking','App\Http\Controllers\Vendor\VendorsController@my_booking');
        Route::post('/accept_booking','App\Http\Controllers\Vendor\VendorsController@accept_booking');
        Route::post('/finish_booking','App\Http\Controllers\Vendor\VendorsController@finish_booking');
        Route::post('/add_review','App\Http\Controllers\Vendor\VendorsController@add_review');
        Route::post('/get_work_days','App\Http\Controllers\Vendor\VendorsController@get_work_days');


    });
});


