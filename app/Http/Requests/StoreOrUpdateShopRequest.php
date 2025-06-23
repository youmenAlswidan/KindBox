<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
         return auth()->check() && auth()->user()->role_id === 2;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
  public function rules()
{
    if ($this->isMethod('POST')) {
        return [
            'shop_name' => 'required|string',
            'description_shop' => 'required|string',
            'location' => 'required|string',
            'phone_number' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image_shop' => 'required|image|max:2048',
        ];
    }

    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
        return [
            'shop_name' => 'sometimes|string',
            'description_shop' => 'sometimes|string',
            'location' => 'sometimes|string',
            'phone_number' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'image_shop' => 'sometimes|image|max:2048',
        ];
    }

    return [];
}

}
