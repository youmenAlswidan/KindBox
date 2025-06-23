<?php

namespace App\Http\Requests;
use App\Models\Product;


use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateGroupOrderRequest extends FormRequest
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
          return [
            'product_id' => 'required|exists:products,id',
            'status' => 'required|in:open,closed',
            'current_user_count' => 'required|integer|min:0',
            'require_user_count' => 'required|integer|min:1',
            'dead_line' => 'required|date|after_or_equal:today',
        ];
                       

     
    
    }
}
