<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'total_price' => $this->total_price,
            'total_quantity' => $this->total_quantity,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
