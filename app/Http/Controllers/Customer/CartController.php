<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCartItemRequest;
use App\Http\Resources\Customer\CartItemResource;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use App\Http\Requests\StoreOrderAddressRequest;
use App\Models\Address;
use App\Models\ShippingCost;



class CartController extends Controller
{
  // إضافة منتج للكارت
public function store(StoreCartItemRequest $request)
{
    $product = Product::with('discount')->findOrFail($request->product_id);

    // التحقق من الشراء الجماعي
    if ($request->is_group_order && !$product->group_price) {
        return response()->json([
            'message' => 'لا يمكن إضافة هذا المنتج كشراء جماعي لأنه غير مفعّل عليه الشراء الجماعي.'
        ], 422);
    }

    if ($request->is_group_order && $product->group_price) {
        // السعر الجماعي إذا طلبه المستخدم
        $price = $product->group_price;
    } else {
        $price = $product->price;

        // التحقق من وجود خصم صالح (ضمن المدة)
        if (
            $product->discount &&
            now()->between($product->discount->start_date, $product->discount->end_date)
        ) {
            $price -= ($price * ($product->discount->discount_value / 100));
        }
    }

    $cartItem = CartItem::where('user_id', auth()->id())
        ->where('product_id', $request->product_id)
        ->where('is_group_order', $request->is_group_order ?? false)
        ->first();

    if ($cartItem) {
        // تحديث السعر باستمرار لضمان توافق الخصم أو السعر الجماعي عند كل إضافة
        $cartItem->quantity += $request->quantity;
        $cartItem->price = $price;
        $cartItem->save();
    } else {
        $cartItem = CartItem::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'is_group_order' => $request->is_group_order ?? false,
            'price' => $price,
        ]);
    }

    return response()->json([
        'message' => 'تمت إضافة المنتج إلى السلة بنجاح'
    ], 201);
}







    // عرض الكارت
public function index()
{
    $userId = auth()->id();

    // 1️⃣ جلب عناصر السلة للمستخدم
    $cartItems = CartItem::where('user_id', $userId)
        ->with('product')
        ->get();

    // 2️⃣ حساب مجموع سعر المنتجات في السلة
    $totalProductsPrice = $cartItems->sum(function ($item) {
        return $item->price * $item->quantity;
    });

    // 3️⃣ جلب آخر عنوان أدخله المستخدم
    $latestAddress = Address::where('user_id', $userId)
        ->latest()
        ->first();

    // 4️⃣ تهيئة متغيرات تكلفة الشحن والسعر النهائي
    $shippingCost = 0;
    $finalTotal = $totalProductsPrice;

    if ($latestAddress) {
        // جلب تكلفة الشحن حسب المدينة
        $shipping = ShippingCost::where('city', $latestAddress->city)->first();

        if ($shipping) {
            $shippingCost = $shipping->cost;
            $finalTotal += $shippingCost; // السعر النهائي = المنتجات + الشحن
        }
    }

    return response()->json([
        'cart_items' => \App\Http\Resources\Customer\CartItemResource::collection($cartItems),
        'total_products_price' => round($totalProductsPrice, 2),
        'shipping_cost' => round($shippingCost, 2),
        'final_total' => round($finalTotal, 2),

    ]);
}






    // إزالة منتج من الكارت
    public function destroy($id)
    {
        $cartItem = CartItem::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $cartItem->delete();

        return response()->json(['message' => 'تم حذف المنتج من الكارت بنجاح']);
    }

    // تفريغ الكارت بالكامل
    public function clear()
    {
        CartItem::where('user_id', auth()->id())->delete();
        return response()->json(['message' => 'تم تفريغ الكارت بالكامل']);
    }



    public function updateQuantity(Request $request, $id)
{
    $request->validate([
        'quantity' => 'required|integer|min:0',
    ]);

    $cartItem = CartItem::where('user_id', auth()->id())->findOrFail($id);

    if ($request->quantity == 0) {
        // حذف تلقائي عند الصفر
        $cartItem->delete();

        return response()->json([
            'message' => 'تم حذف المنتج من السلة لأن الكمية أصبحت صفر',
        ]);
    } else {
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'message' => 'تم تحديث الكمية بنجاح',
            'cart_item' => new CartItemResource($cartItem),
        ]);
    }
}








}
