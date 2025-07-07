<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray($request)
    {
        $product = $this->product;
        $price = $product->price;

        // ✅ حساب السعر في حال الشراء الجماعي
        if ($this->is_group_order && $product->group_price) {
            $price = $product->group_price;
        } else {
            // ✅ تطبيق الخصم فقط في حال كان ضمن المدة
            if (
                $product->discount &&
                now()->between($product->discount->start_date, $product->discount->end_date)
            ) {
                $price -= ($price * ($product->discount->discount_value / 100));
            }
        }

        $productData = [
            'id' => $product->id,
            'name' => $product->product_name,
        ];

        // ✅ حذف حقل الصورة إذا كانت null أو فارغة
        if (!empty($product->image)) {
            $productData['image'] = $product->image;
        }

        $result = [
            'id' => $this->id,
            'product' => $productData,
            'quantity' => $this->quantity,
            'price' => round($price, 2),
            'total_price' => round($price * $this->quantity, 2),
        ];

        // ✅ إضافة حقل is_group_order فقط إذا كانت قيمته غير صفر (true)
        if ($this->is_group_order) {
            $result['is_group_order'] = $this->is_group_order;
        }

        return $result;
    }
}
