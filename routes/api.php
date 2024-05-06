<?php

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
Route::post('/v1/login',[\App\Http\Controllers\LoginController::class,'login']);
Route::post('/v1/logout',[\App\Http\Controllers\LoginController::class,'logout']);

Route::get('/v1/cancel_route/{id}',[\App\Http\Controllers\RouteController::class,'cancelRoute']);
Route::get('/v1/start_route/{id}',[\App\Http\Controllers\RouteController::class,'startRoute']);
Route::apiResource('/v1/routes',\App\Http\Controllers\RouteController::class);
Route::get('/v1/is_distance',[\App\Http\Controllers\RouteController::class,'isDistance']);
Route::apiResource('/v1/bookings',\App\Http\Controllers\BookingsController::class);
Route::apiResource('/v1/cities',\App\Http\Controllers\CityController::class);
Route::apiResource('/v1/shuttle_type',\App\Http\Controllers\ShuttleTypeController::class);
