<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerProductResource extends JsonResource
{
    public function toArray($request)
    {
        $price = $this->price;

        // إذا مفعل الشراء الجماعي للمنتج ويوجد سعر جماعي
        if ($this->has_group_purchase && $this->group_price !== null) {
            $price = $this->group_price;
        }

        // إذا هناك خصم ساري، نطبقه
        if ($this->discount) {
            $discountValue = $this->discount->discount_value; // مثلاً 10.00 يعني 10%
            $price = $price - ($price * ($discountValue / 100));
        }

        return [
            'id' => $this->id,
            'name' => $this->product_name,
            'image' => $this->when($this->image !== null, $this->image),
            'final_price' => round($price, 2),

        ];
    }
}
