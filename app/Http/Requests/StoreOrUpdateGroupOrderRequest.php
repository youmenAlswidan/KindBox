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
   public function rules(): array
{
    // القواعد الأساسية بلا "required"
    $rules = [
        'product_id'         => 'exists:products,id',
        'status'             => 'in:open,closed',
        'current_user_count' => 'integer|min:0',
        'require_user_count' => 'integer|min:1',
        'dead_line'          => 'date|after_or_equal:today',
    ];

    // إذا كان الطلب POST → اجعل كل شيء مُلزِم
    if ($this->isMethod('post')) {
        foreach ($rules as $field => $rule) {
            $rules[$field] = "required|$rule";
        }
    }

    // إذا كان PUT أو PATCH → أضف "sometimes|" لنجعلها اختيارية
    if ($this->isMethod('put') || $this->isMethod('patch')) {
        foreach ($rules as $field => $rule) {
            $rules[$field] = "sometimes|$rule";
        }
    }

    return $rules;
}

}
