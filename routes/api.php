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
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\CustomerProductController;
use App\Http\Controllers\Customer\CustomerRatingController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Customer\PaymentController;











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



    // Shop Manager routes
    Route::apiResource('shops', ShopController::class);
    Route::apiResource('shop-documents', ShopDocumentController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('group-orders', GroupOrderController::class);
});





Route::middleware('auth:sanctum')->group(function () {
    Route::post('/chat', [ChatController::class, 'chat']);
    Route::get('/chat/history', [ChatController::class, 'history']);
});


//customer

Route::prefix('customer')->group(function () {

    // 📦 المنتجات
    Route::get('products', [CustomerProductController::class, 'index']); // كل المنتجات
    Route::get('products/group-purchase', [CustomerProductController::class, 'groupPurchaseProducts']); // منتجات الشراء الجماعي
    Route::get('products/{product}', [CustomerProductController::class, 'show']); // تفاصيل منتج معين

    // ⭐ التقييمات
    Route::get('products/{product}/ratings', [CustomerRatingController::class, 'index']);

    // 🗂️ المنتجات حسب التصنيف
    Route::get('categories/{category}/products', [CustomerProductController::class, 'productsByCategory']);

    // 🛡️ مسارات تتطلب تسجيل الدخول
    Route::middleware('auth:sanctum')->group(function () {

        // ⭐ إضافة تقييم
        Route::post('products/{product}/ratings', [CustomerRatingController::class, 'store']);

        // 🛒 السلة
        Route::prefix('cart')->group(function () {
            Route::get('/', [CartController::class, 'index']);             // عرض السلة
            Route::post('/add', [CartController::class, 'store']);       // إضافة منتج للسلة
            Route::put('/update-quantity/{id}', [CartController::class, 'updateQuantity']);

            Route::delete('/remove/{id}', [CartController::class, 'destroy']); // حذف منتج معين
            Route::delete('/clear', [CartController::class, 'clear']);         // إفراغ السلة


            Route::post('/address', [AddressController::class, 'storeAddressForOrder']);
            Route::post('/customer/address', [AddressController::class, 'store']);



            Route::get('/checkout', [CheckoutController::class, 'getCheckoutDetails']);

             Route::post('/checkout/pay', [CheckoutController::class, 'checkout']);



            Route::post('/notifications/send', [NotificationController::class, 'sendNotification']);
        });
    });
});
