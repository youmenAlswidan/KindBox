<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    public function toArray( $request): array
    {
        return [
            'id' => $this->id,
            'shop_name' => $this->shop_name,
            'image_url' => $this->image_shop ? asset('storage/' . $this->image_shop) : null,
            'description' => $this->description_shop,
            'location' => $this->location,
            'phone' => $this->phone_number,
            'status' => $this->status,
            'category' => $this->category?->name,
            'created_at' => $this->created_at->toDateString(),
        ];
    }
}

