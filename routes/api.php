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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user()->load('role');
});


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

 // Driver routes
    Route::middleware(['auth:sanctum', 'role:driver'])->prefix('driver')->group(function () {
    Route::get('/deliveries', [DeliveryController::class, 'index']); // عرض الطلبات المخصصة له
    Route::patch('/deliveries/{id}/status', [DeliveryController::class, 'updateStatus']); // تحديث حالة الطلب
    Route::post('/location', [DeliveryController::class, 'updateLocation']); // اختيارية
    Route::post('/deliveries/{id}/deliver', [DeliveryController::class, 'markAsDelivered']); // توصيل الطلب 
});



