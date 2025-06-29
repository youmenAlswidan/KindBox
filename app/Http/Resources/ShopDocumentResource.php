<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopDocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( $request): array
    {
        return [
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'document_type' => $this->document_type,
            'file_url' => asset('storage/' . $this->file_path_document),
            'status' => $this->status,
            
        ];
    }
}
