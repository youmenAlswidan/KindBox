<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateShopDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return  true;
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
                'document_type' => 'required|string',
                'file_path_document' => 'required|file|max:10240',
               
            ];
        }

       if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
    return [
        'document_type' => 'sometimes|string',
        'file_path_document' => 'sometimes|file|max:10240',
    ];
}

        return [

         
    ];
    }
}
