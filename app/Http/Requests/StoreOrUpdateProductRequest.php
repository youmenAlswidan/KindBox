<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('POST')) {
            return [
                'category_id' => 'required|exists:categories,id',
                'product_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|image|max:2048',
                'remove_discount' => 'nullable|boolean',
                'has_group_purchase' => 'nullable|boolean',

                // إذا تم تفعيل الشراء الجماعي
                'group_price' => 'required_if:has_group_purchase,1|numeric|min:0',
                'group_min_users' => 'required_if:has_group_purchase,1|integer|min:2',
                'dead_line' => 'required_if:has_group_purchase,1|date|after:today',
            ];
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules = [
                'category_id' => 'sometimes|exists:categories,id',
                'product_name' => 'sometimes|string|max:255',
                'description' => 'sometimes|nullable|string',
                'price' => 'sometimes|numeric|min:0',
                'image' => 'sometimes|nullable|image|max:2048',
                'remove_discount' => 'sometimes|boolean',
                'has_group_purchase' => 'sometimes|boolean',
            ];

            // خصائص الخصم فقط في التعديل
            if ($this->hasAny(['discount_price', 'discount_start', 'discount_end'])) {
                $rules['discount_price'] = 'nullable|numeric|lt:price';
                $rules['discount_start'] = 'nullable|date';
                $rules['discount_end'] = 'nullable|date|after_or_equal:discount_start';
            }

            if ($this->input('has_group_purchase')) {
                $rules['group_price'] = 'required|numeric|min:0';
                $rules['group_min_users'] = 'required|integer|min:2';
                $rules['dead_line'] = 'required|date|after:today';
            }

            return $rules;
        }

        return [];
    }
}
