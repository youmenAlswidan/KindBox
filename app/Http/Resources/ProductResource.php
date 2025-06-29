<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'description' => $this->description,
            'price' => $this->price,
            'image_url' => $this->image ? asset('storage/' . $this->image) : null,
            'has_group_purchase' => $this->has_group_purchase,
            'group_price' => $this->group_price,
            'group_min_users' => $this->group_min_users,
            'group_order' => $this->has_group_purchase && $this->groupOrders ? [
                'id' => $this->groupOrders->id,
                'status' => $this->groupOrders->status,
                'dead_line' => $this->groupOrders->dead_line,
                'current_user_count' => $this->groupOrders->current_user_count,
                'require_user_count' => $this->groupOrders->require_user_count,
                
            ] : null,
            'category' => [
                
                'category_name' => $this->category->name,

            ],
                 'discount' => $this->discount ? [
                'id' => $this->discount->id,
                'discount_value' => $this->discount->discount_value,
                'start_date' => $this->discount->start_date,
                'end_date' => $this->discount->end_date,
            ] : null,

            
            'final_price' => $this->discount &&
                            now()->between($this->discount->start_date, $this->discount->end_date)
                            ? $this->discount->discount_value
                            : $this->price,

            
        ];
           

           
         
    }
}
