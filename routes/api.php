<?php


use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Driver\DeliveryController;



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






Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

//Driver 
Route::middleware(['auth:sanctum', 'role:driver'])->prefix('driver')->group(function () {
    Route::get('/deliveries', [DeliveryController::class, 'index']);
    Route::post('/deliveries/{id}/status', [DeliveryController::class, 'updateStatus']);
});



