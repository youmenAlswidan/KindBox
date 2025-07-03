<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
               
            'product_name' => $this->product_name,
            'description' => $this->description,
            'price' => $this->price,
            'has_group_purchase' => $this->has_group_purchase,
            'final_price' => $this->discount &&
                            now()->between($this->discount->start_date, $this->discount->end_date)
                            ? $this->discount->discount_value
                            : $this->price,
        ];

        if ($this->image) {
            $data['image_url'] = asset('storage/' . $this->image);
        }

     
        if ($this->has_group_purchase) {
            $data['group_price'] = $this->group_price;
            $data['group_min_users'] = $this->group_min_users;

            if ($this->groupOrders) {
                $data['group_order'] = [
                    'status' => $this->groupOrders->status,
                    'dead_line' => $this->groupOrders->dead_line,
                    'current_user_count' => $this->groupOrders->current_user_count,
                    'require_user_count' => $this->groupOrders->require_user_count,
                ];
            }
        }

      if ($this->category) {
    $data['category'] = $this->category->name;
}


       if ($this->discount) {
    $data['discount'] = [
        'id' => $this->discount->id,
        'discount_value' => $this->discount->discount_value,
        'start_date' => $this->discount->start_date,
        'end_date' => $this->discount->end_date,
    ];
} 


        return $data;
    }
}
