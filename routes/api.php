<?php


use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Driver\DeliveryController;
use App\Http\Controllers\ShopManger\ShopController;
use App\Http\Controllers\ShopManger\ShopDocumentController;
use App\Http\Controllers\ShopManger\ProductController;
use App\Http\Controllers\ShopManger\DiscountController;
use App\Http\Controllers\ShopManger\GroupOrderController;

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


Route::middleware('auth:sanctum')->group(function () {

   // Route::post('/logout', [AuthController::class, 'logout']);

    // Shop Manager routes
    Route::resource('shops', ShopController::class);
    Route::resource('shop-documents', ShopDocumentController::class);
    Route::resource('products', ProductController::class);
    Route::apiResource('discounts', DiscountController::class);
    Route::apiResource('group-orders', GroupOrderController::class);
});

