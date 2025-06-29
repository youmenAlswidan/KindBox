<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product' => [
                'id' => $this->product->id,
                'name' => $this->product->product_name,
                // أضف حقول المنتج التي تريد عرضها
            ],
            'status' => $this->status,
            'current_user_count' => $this->current_user_count,
            'require_user_count' => $this->require_user_count,
            'dead_line' => $this->dead_line,
           
        ];
    }
}
