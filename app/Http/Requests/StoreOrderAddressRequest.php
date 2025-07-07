<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderAddressRequest extends FormRequest
{
    public function authorize()
    {
        return true; // للسماح لكل المستخدمين بالطلب
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'address' => 'required|string',
        ];
    }
}
