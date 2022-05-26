<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        return [
            'priority' => 'sometimes|nullable|numeric',
            'description' => 'sometimes|nullable|max:255',
            'icon' => 'sometimes|nullable|image|mimes:jpg,png,jpeg|max:2048',
            'banner' => 'sometimes|nullable|image|mimes:jpg,png,jpeg|max:2048',
        ];
    }
}
