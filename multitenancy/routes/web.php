<?php

use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Landlord app 
Route::get('/', function () {
    return view('welcome');
});
// Auth routes
Route::get('/auth', [AuthController::class, 'show'])->name('auth');
Route::post('/central-login', [AuthController::class, 'logIn'])->name('central-login');
Route::get('/redirect-user/{globalUserId}/to-tenant/{tenant}', [AuthController::class, 'redirectUserToTenant'])->name('redirect-user-to-tenant');




Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
