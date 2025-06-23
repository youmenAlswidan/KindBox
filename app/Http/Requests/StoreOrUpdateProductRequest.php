<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

    $rules = [
        'category_id' => 'required|exists:categories,id',
        'product_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|max:2048',
        'has_group_purchase' => 'nullable|boolean',
    ];
 if ($this->input('has_group_purchase')) {
        $rules['group_price'] = 'required|numeric|min:0';
        $rules['group_min_users'] = 'required|integer|min:2';
    }
   

    return $rules;
}

       
    }

