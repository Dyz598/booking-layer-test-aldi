<?php

use App\Http\Controllers\BlockController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OccupancyRateController;
use App\Http\Controllers\RoomController;
use App\Http\Middleware\DatabaseTransaction;
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

Route::apiResource('rooms', RoomController::class)
    ->except(['update', 'destroy']);

Route::apiResource('bookings', BookingController::class)
    ->middleware(DatabaseTransaction::class)
    ->only(['store', 'update']);

Route::apiResource('blocks', BlockController::class)
    ->middleware(DatabaseTransaction::class)
    ->only(['store', 'update']);

Route::get('daily-occupancy-rates/{date}', [OccupancyRateController::class, 'daily']);
Route::get('monthly-occupancy-rates/{month_year}', [OccupancyRateController::class, 'monthly']);
