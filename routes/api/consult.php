<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Consult\UserController;
use App\Http\Controllers\Consult\AppointmentController;
use App\Http\Controllers\Consult\PostController;
use App\Http\Controllers\Consult\EducationController;
use App\Http\Controllers\Consult\ExperienceController;
use App\Http\Controllers\Consult\CourseController;
use App\Http\Controllers\Consult\MembershipController;
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

Route::post('consult/clear',function()
{
    \Artisan::call('config:clear');
});
Route::post('consult/consultRegister',[UserController::class,'register']);
Route::post('consult/consultLogin',[UserController::class,'login']);
Route::post('consult/verifyMobile',[UserController::class,'verifyMobile']);
Route::post('consult/home',[AppointmentController::class,'home']);
Route::post('consult/appointments',[AppointmentController::class,'appointments']);
Route::post('consult/appointmentsByDate',[AppointmentController::class,'appointmentsByDate']);
Route::post('consult/getAppointmentById',[AppointmentController::class,'getAppointmentById']);
Route::post('consult/rejectAppointment',[AppointmentController::class,'rejectAppointment']);
Route::post('consult/acceptAppointment',[AppointmentController::class,'acceptAppointment']);




Route::post('consult/posts',[PostController::class,'myPosts']);
Route::post('consult/addPost',[PostController::class,'addPost']);
Route::post('consult/addQa',[PostController::class,'addQa']);
Route::post('consult/qas',[PostController::class,'myQas']);


Route::post('consult/chats',[UserController::class,'chats']);
Route::post('consult/getProfile',[UserController::class,'Profile']);
Route::post('consult/setProfile',[UserController::class,'setProfile']);
Route::post('consult/setData',[UserController::class,'setProfile']);
Route::post('consult/setStatus',[UserController::class,'setStatus']);
Route::post('consult/setPhoto',[UserController::class,'setPhoto']);
Route::post('consult/getServices',[UserController::class,'getServices']);
Route::post('consult/setServiceStatus',[UserController::class,'setServiceStatus']);

Route::post('consult/search',[UserController::class,'search']);
Route::post('consult/search','App\Http\Controllers\User\UserController@search');

Route::post('consult/activeConsult','App\Http\Controllers\User\ConsultController@activeConsult');
Route::post('consult/allConsult','App\Http\Controllers\User\ConsultController@allConsult');
Route::post('consult/showConsultPage','App\Http\Controllers\User\ConsultController@showConsultPage');


Route::post('consult/educations',[EducationController::class,'index']);
Route::post('consult/showEducation',[EducationController::class,'show']);
Route::post('consult/addEducation',[EducationController::class,'store']);
Route::post('consult/deleteEducation',[EducationController::class,'destroy']);
Route::post('consult/updateEducation',[EducationController::class,'update']);

Route::post('consult/courses',[CourseController::class,'index']);
Route::post('consult/showCourse',[CourseController::class,'show']);
Route::post('consult/addCourse',[CourseController::class,'store']);
Route::post('consult/deleteCourse',[CourseController::class,'destroy']);
Route::post('consult/updateCourse',[CourseController::class,'update']);

Route::post('consult/experiences',[ExperienceController::class,'index']);
Route::post('consult/showExperience',[ExperienceController::class,'show']);
Route::post('consult/addExperience',[ExperienceController::class,'store']);
Route::post('consult/deleteExperience',[ExperienceController::class,'destroy']);
Route::post('consult/updateExperience',[ExperienceController::class,'update']);

Route::post('consult/memberships',[MembershipController::class,'index']);
Route::post('consult/showMembership',[MembershipController::class,'show']);
Route::post('consult/addMembership',[MembershipController::class,'store']);
Route::post('consult/deleteMembership',[MembershipController::class,'destroy']);
Route::post('consult/updateMembership',[MembershipController::class,'update']);


Route::post('consult/notifications',[UserController::class,'notifications']);

Route::post('consult/getPosts','App\Http\Controllers\User\PostController@index');
Route::post('consult/getPostDetails','App\Http\Controllers\User\PostController@getPostDetails');
Route::post('/addToWishlist','App\Http\Controllers\User\UserController@addToWishlist');
Route::post('/getWishlist','App\Http\Controllers\User\UserController@getWishlist');
Route::post('consult/getQas','App\Http\Controllers\User\QaController@index');


Route::post('consult/buildToken','App\Http\Controllers\Consult\UserController@buildToken');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
