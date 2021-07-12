<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoomController;
use App\Http\Middleware\BearerToken;
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


Route::post('signup', [AuthController::class, 'create']);

Route::post('login', [AuthController::class, 'login']);

Route::middleware([BearerToken::class])->group(function () {

    Route::patch('logout', [AuthController::class, 'logout']);

    Route::post('add-room', [RoomController::class, 'add_room']);

    Route::post('update-room-availability', [RoomController::class, 'update_available_rooms']);

    Route::post('book-room', [BookingController::class, 'book_room']);

    Route::post('vacate-room', [BookingController::class, 'vacate_room']);

    Route::get('available-rooms', [BookingController::class, 'available_rooms']);
});
