<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/get_accounts', [App\Http\Controllers\UserController::class, 'get_accounts'])->name('get_accounts');
Route::get('/get_employees', [App\Http\Controllers\UserController::class, 'get_employees'])->name('get_employees');
Route::get('/get_reads', [App\Http\Controllers\UserController::class, 'get_reads'])->name('get_reads');

Auth::routes();
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('show_users');
    Route::get('/reads', [App\Http\Controllers\UserController::class, 'reads'])->name('reads');
    Route::get('/employees', [App\Http\Controllers\UserController::class, 'employees'])->name('employees');
    Route::post('/import_accounts', [App\Http\Controllers\UserController::class, 'import_accounts'])->name('import_accounts');
    Route::post('/import_employees', [App\Http\Controllers\UserController::class, 'import_employees'])->name('import_employees');
    Route::post('/add_account', [App\Http\Controllers\UserController::class, 'add_account'])->name('add_account');
    Route::get('/add_reads/{month}/{year}', [App\Http\Controllers\UserController::class, 'add_reads'])->name('add_reads');

});

