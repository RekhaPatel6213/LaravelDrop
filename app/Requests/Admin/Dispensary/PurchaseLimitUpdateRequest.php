<?php

namespace App\Http\Requests\Admin\Dispensary;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseLimitUpdateRequest extends FormRequest
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
            'state' => 'sometimes|max:255',
            'flower_rec_limit' => 'sometimes|numeric',
            'flower_med_limit' => 'sometimes|numeric',
            'con_rec_limit' => 'sometimes|numeric',
            'con_med_limit' => 'sometimes|numeric',
            'plant_rec_limit' => 'sometimes|numeric',
            'plant_med_limit' => 'sometimes|numeric',
        ];


        return $validationRules;
    }
}
