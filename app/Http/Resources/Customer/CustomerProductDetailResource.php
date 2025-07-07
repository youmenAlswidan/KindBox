<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Customer\CustomerRatingResource;
use App\Http\Resources\Customer\CustomerProductResource;

class CustomerProductDetailResource extends JsonResource
{
    public function toArray($request)
    {
        // استخراج قيمة الخصم الحالي إن وجد
        $discountValue = $this->discount ? $this->discount->discount_value : 0;
        $hasDiscount = $discountValue > 0;

        // حساب السعر بعد الخصم إن وجد
        $priceAfterDiscount = $hasDiscount
            ? ($this->price - ($this->price * ($discountValue / 100)))
            : $this->price;

        // استخراج السعر الجماعي إن كان مفعلاً على المنتج
        $groupPurchasePrice = $this->has_group_purchase ? $this->group_price : null;

        // جلب أحدث تقييمين للمنتج
        $latestRatings = $this->ratings()->latest()->take(2)->get();

        // جلب منتجات أخرى من نفس المتجر مع استثناء هذا المنتج الحالي
        $sameShopProducts = $this->shop
            ? $this->shop->products()->where('id', '!=', $this->id)->take(10)->get()
            : collect();

        // إعداد البيانات الأساسية
        $data = [
            'id' => $this->id,
            'name' => $this->product_name,
            'description' => $this->description,
            'original_price' => $this->price,
            'price_after_discount' => $priceAfterDiscount,
        ];

        // إضافة حالة الخصم إن وجد
        if ($hasDiscount) {
            $data['has_discount'] = true;
        }

        // إضافة بيانات الشراء الجماعي إن كان مفعلاً
        if ($this->has_group_purchase) {
            $data['has_group_purchase'] = true;

            if ($groupPurchasePrice !== null) {
                $data['group_purchase_price'] = $groupPurchasePrice;
            }

            // إظهار إمكانية اختيار الشراء الجماعي
            $data['can_choose_group_purchase'] = true;

            // جلب معلومات الطلب الجماعي الحالي إن وجد
            $groupOrder = $this->groupOrders()
                ->where('status', 'open')
                ->whereDate('dead_line', '>=', now())
                ->first();

            if ($groupOrder) {
                $data['group_purchase_current_user_count'] = $groupOrder->current_user_count; // عدد المنضمين الحالي
                $data['group_purchase_required_user_count'] = $groupOrder->require_user_count; // العدد المطلوب
                $data['group_purchase_deadline'] = $groupOrder->dead_line; // الموعد النهائي
            }
        }

        // إضافة اسم وموقع المتجر إن وجد
        if ($this->shop && $this->shop->shop_name) {
            $data['shop_name'] = $this->shop->shop_name;
        }

        if ($this->shop && $this->shop->location) {
            $data['shop_location'] = $this->shop->location;
        }

        // إضافة رابط الصورة إن وجدت
        if ($this->image) {
            $data['image'] = url('storage/' . $this->image);
        }

        // إضافة أحدث التقييمات
        $data['latest_ratings'] = CustomerRatingResource::collection($latestRatings);

        // إضافة منتجات أخرى من نفس المتجر للعرض في صفحة التفاصيل
        $data['same_shop_products'] = CustomerProductResource::collection($sameShopProducts);

        return $data;
    }
}
