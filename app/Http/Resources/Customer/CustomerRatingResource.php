<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerRatingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user_name' => $this->user ? ($this->user->first_name . ' ' . $this->user->last_name) : 'مستخدم',
            'rating' => $this->rating,
            'comment' => $this->comment,
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
