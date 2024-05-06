<?php

use Illuminate\Support\Facades\Route;

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
Route::middleware('auth')->group(function(){
    Route::get('/booking/{id}',[\App\Http\Controllers\BusController::class,'booking'])->name('booking');
    Route::post('/booking',[\App\Http\Controllers\BusController::class,'doBooking'])->name('doBooking');
    Route::get('/bookings',[\App\Http\Controllers\BusController::class,'bookings'])->name('bookings');
    Route::get('/booking-details/{id}',[\App\Http\Controllers\BusController::class,'details'])->name('details');
    Route::post('/change-booking/{id}',[\App\Http\Controllers\BusController::class,'changeBooking'])->name('changeBooking');
    Route::post('/delete-booking/{id}',[\App\Http\Controllers\BusController::class,'deleteBooking'])->name('deleteBooking');
});
Route::get('/', [\App\Http\Controllers\BusController::class,'index'])->name('index');
Route::get('/search', [\App\Http\Controllers\BusController::class,'search'])->name('search');
Route::get('/login',[\App\Http\Controllers\AuthController::class,'login'])->name('login');
Route::post('/login',[\App\Http\Controllers\AuthController::class,'doLogin'])->name('doLogin');
Route::get('/logout',[\App\Http\Controllers\AuthController::class,'logout'])->name('logout');

