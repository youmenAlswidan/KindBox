<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Order;
use App\Models\Payment;
use App\Models\GroupOrder;
use App\Models\OrderItem;
use App\Models\GroupOrderMember;
use App\Models\Address;
use App\Models\ShippingCost;
use App\Http\Controllers\NotificationController;

class CheckoutController extends Controller
{

    public function getCheckoutDetails(Request $request)
    {
        $user = $request->user();
        $splitGroupPurchase = $request->boolean('split_group_purchase', false);

        $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();

        // حساب سعر المنتجات
        $totalProductsPrice = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // جلب أحدث عنوان للمستخدم لحساب تكلفة الشحن
        $latestAddress = Address::where('user_id', $user->id)->latest()->first();

        $shippingCost = 0;
        if ($latestAddress) {
            $shipping = ShippingCost::where('city', $latestAddress->city)->first();
            if ($shipping) {
                $shippingCost = $shipping->cost;
            }
        }

        $finalTotal = $totalProductsPrice + $shippingCost;

        // منطق الفصل بين منتجات الشراء الجماعي والعادي
        $hasGroupItems = $cartItems->contains(fn($item) => $item->is_group_order);
        $hasNormalItems = $cartItems->contains(fn($item) => !$item->is_group_order);
        $canSplit = $hasGroupItems && $hasNormalItems;

        if (!$canSplit) {
            return response()->json([
                'can_split' => false,
                'split_group_purchase' => false,
                'final_total' => round($finalTotal, 2),
                'message' => 'لا يوجد داعٍ للفصل، جميع المنتجات من نفس النوع.',
            ]);
        }

        if ($splitGroupPurchase) {
            $normalItemsTotal = $cartItems->where('is_group_order', false)->sum(fn($item) => $item->price * $item->quantity);
            $groupItemsTotal = $cartItems->where('is_group_order', true)->sum(fn($item) => $item->price * $item->quantity);

            return response()->json([
                'can_split' => true,
                'split_group_purchase' => true,
                'normal_items_total' => round($normalItemsTotal, 2),
                'group_items_total' => round($groupItemsTotal, 2),
                'shipping_cost' => round($shippingCost, 2),
                'final_total' => round($finalTotal, 2),
                'message' => 'تم فصل منتجات الشراء الجماعي عن المنتجات العادية للسعر.'
            ]);
        } else {
            return response()->json([
                'can_split' => true,
                'split_group_purchase' => false,
                'shipping_cost' => round($shippingCost, 2),
                'final_total' => round($finalTotal, 2),
                'message' => 'يمكنك اختيار فصل الطلب قبل الدفع.'
            ]);
        }
    }


    private function getCartDataForUser($userId)
    {
        $cartItems = \App\Models\CartItem::where('user_id', $userId)->with('product')->get();

        $totalProductsPrice = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // تكلفة الشحن هنا صفر مؤقتا (يمكن تعديلها لاحقاً)
        $shippingCost = 0;

        $finalTotal = $totalProductsPrice + $shippingCost;

        return [
            'cart_items' => \App\Http\Resources\Customer\CartItemResource::collection($cartItems),
            'total_products_price' => round($totalProductsPrice, 2),
            'shipping_cost' => round($shippingCost, 2),
            'final_total' => round($finalTotal, 2),
        ];
    }




    public function checkout(Request $request)
    {
        $user = $request->user();
        $splitGroupPurchase = $request->boolean('split_group_purchase', false);

        $cartItems = CartItem::where('user_id', $user->id)->with('product.shop')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'السلة فارغة.'], 400);
        }

        DB::beginTransaction();

        try {
            $totalPrice = 0;
            $totalQuantity = 0;
            $groupOrderId = null;
            $hasGroupPurchase = false;
            $groupOrderComplete = false;

            foreach ($cartItems as $item) {
                $totalPrice += $item->price * $item->quantity;
                $totalQuantity += $item->quantity;

                if ($item->product->has_group_purchase && $item->is_group_order) {
                    $hasGroupPurchase = true;
                }
            }

            $shippingCost = 0;
            $latestAddress = Address::where('user_id', $user->id)->latest()->first();
            if ($latestAddress) {
                $shipping = ShippingCost::where('city', $latestAddress->city)->first();
                if ($shipping) {
                    $shippingCost = $shipping->cost;
                }
            }

            $finalAmount = $totalPrice + $shippingCost;

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'total_quantity' => $totalQuantity,
                'group_orders_id' => null,
                'status' => 'pending',
                'split_option' => $splitGroupPurchase,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'price_item' => $item->price,
                    'quantity_item' => $item->quantity,
                ]);

                if ($item->product->has_group_purchase && $item->is_group_order) {
                    $groupOrder = GroupOrder::where('product_id', $item->product_id)
                        ->whereIn('status', ['open', 'active'])
                        ->where('dead_line', '>=', now())
                        ->first();

                    if ($groupOrder) {
                        $groupOrder->increment('current_user_count', $item->quantity);

                        GroupOrderMember::create([
                            'user_id' => $user->id,
                            'group_order_id' => $groupOrder->id,
                            'joined_at' => now(),
                        ]);

                        if ($groupOrder->current_user_count >= $groupOrder->require_user_count) {
                            $groupOrder->update(['status' => 'completed']);
                            $groupOrderComplete = true;
                        }

                        $groupOrderId = $groupOrder->id;
                    }
                }
            }

            if ($groupOrderId) {
                $order->update(['group_orders_id' => $groupOrderId]);
            }

            Payment::create([
                'order_id' => $order->id,
                'amount' => $finalAmount,
                'payment_method' => 'stripe_test',
                'payment_status' => 'paid',
                'payment_date' => now(),
            ]);

            CartItem::where('user_id', $user->id)->delete();

            $shopManagerId = $cartItems->first()->product->shop->user_id ?? null;

            if ($shopManagerId) {
                if (!$hasGroupPurchase) {
                    NotificationController::notifyUser(
                        $shopManagerId,
                        "لديك طلب جديد (#{$order->id}) جاهز ."
                    );
                } elseif ($splitGroupPurchase) {
                    NotificationController::notifyUser(
                        $shopManagerId,
                        "لديك طلب جديد:(#{$order->id})  المنتجات العادية جاهزة "
                    );
                } else {
                    NotificationController::notifyUser(
                        $shopManagerId,
                        "لديك طلب جديد معلق في انتظار اكتمال عدد الشراء الجماعي."
                    );
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'تم الدفع بنجاح.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'حدث خطأ أثناء عملية الدفع .',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
