<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'product_id' => $this->product_id,
            'product_name' => $this->product->name ?? null,
            'product_image' => $this->product->image ?? null,
            'price_item' => $this->price_item,
            'quantity_item' => $this->quantity_item,
        ];
    }
}
