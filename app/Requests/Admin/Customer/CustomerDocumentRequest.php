<?php

namespace App\Http\Requests\Admin\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerDocumentRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $validationRules = [
            'document_file' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'customer_id' => 'required|exists:dispensary_customers,id,dispensary_id,' . tenant('id'),
            'document_type' => 'required|in:card,medical,other',
        ];

        return $validationRules;
    }
}
