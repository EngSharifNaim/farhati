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
    'prefix' => 'user'
],function (){
    Route::post('/home','App\Http\Controllers\User\UserController@home');
    Route::post('/login','App\Http\Controllers\User\LoginController@login');
    Route::post('/register','App\Http\Controllers\User\UserController@register');
    Route::post('/forgetPassword','App\Http\Controllers\User\UserController@forgetPassword');
    Route::post('/resetPassword','App\Http\Controllers\User\UserController@resetPassword');
    Route::post('/verify_mobile','App\Http\Controllers\User\UserController@verify_mobile');
    Route::post('/send_otp','App\Http\Controllers\User\UserController@send_otp');
    Route::post('/search','App\Http\Controllers\User\UserController@search');

    Route::post('/get_sliders','App\Http\Controllers\User\UserController@get_sliders');
    Route::post('/get_top_rated','App\Http\Controllers\User\VendorsController@get_top_rated');
    Route::post('/get_categories','App\Http\Controllers\User\VendorsController@get_categories');
    Route::post('/get_vendor_by_id','App\Http\Controllers\User\VendorsController@get_vendor_by_id');



    Route::group(['middleware' => 'auth:api'],function (){
        // Consults routes
        Route::post('/add_direct_order','App\Http\Controllers\User\OrderController@add_direct_order');
        Route::post('/add_order','App\Http\Controllers\User\OrderController@add_order');
        Route::get('/my_orders','App\Http\Controllers\User\OrderController@my_orders');
        Route::get('/my_events','App\Http\Controllers\User\OrderController@my_events');
        Route::get('/my_direct_orders','App\Http\Controllers\User\OrderController@my_direct_orders');
        Route::get('/order_by_id/{order_id}','App\Http\Controllers\User\OrderController@order_by_id');
        Route::get('/event_by_id/{event_id}','App\Http\Controllers\User\OrderController@event_by_id');
        Route::get('/offers_by_category_id/{order_id}/{category_id}','App\Http\Controllers\User\OrderController@offers_by_category_id');
        Route::get('/offers_by_event_id/{order_id}/{event_id}','App\Http\Controllers\User\OrderController@offers_by_event_id');
        Route::get('/accept_offer/{offer_id}','App\Http\Controllers\User\OrderController@accept_offer');
        Route::get('/accept_direct_order/{order_id}','App\Http\Controllers\User\OrderController@accept_direct_order');


        Route::get('/get_notifications/','App\Http\Controllers\User\NotificationController@get_notifications');


        Route::post('/change_password/','App\Http\Controllers\User\UserController@change_password');
        Route::post('/set_profile/','App\Http\Controllers\User\UserController@set_profile');
        Route::post('/set_photo/','App\Http\Controllers\User\UserController@set_photo');
        Route::get('/get_profile/','App\Http\Controllers\User\UserController@get_profile');
        Route::post('/get_vendor_by_service/','App\Http\Controllers\User\UserController@get_vendor_by_service');
        Route::post('/get_vendor_by_keyword/','App\Http\Controllers\User\UserController@get_vendor_by_keyword');

        Route::post('/add_vendor_rate/','App\Http\Controllers\User\UserController@add_vendor_rate');
        Route::get('/get_rates_by_vendor_id/{vendor_id}','App\Http\Controllers\User\UserController@get_rates_by_vendor_id');


        Route::get('/my_favorites/','App\Http\Controllers\User\UserController@my_favorites');
        Route::post('/add_to_favorite/','App\Http\Controllers\User\UserController@add_to_favorite');
        Route::delete('/delete_from_favorite/{favorite_id}','App\Http\Controllers\User\UserController@delete_from_favorite');


    });
});


