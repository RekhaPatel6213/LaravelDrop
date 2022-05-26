<?php

namespace App\Http\Requests\Hub;

use App\Models\Hub\PromoCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Illuminate\Validation\Rule;

class HourSetRequest extends FormRequest
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
        $routeName = $this->route()->getName() ?? '';
        $validationRules =  [
            'data.*.name' => 'required|max:50',
            'data.*.territories' => 'required|array',
            'data.*.territories.*' => 'exists:territories,id',
            'data.*.timings.*.from_time' => 'required|date_format: "H:i:s"',
            'data.*.timings.*.to_time' => 'required|date_format: "H:i:s"',
        ];

        if (in_array($routeName, ['shop_timings.delete'])) {
            $validationRules = [
                'hourSetId' => 'required|numeric|exists:dispensary_hour_sets,id'
            ];
        }

        return $validationRules;
    }

    protected function prepareForValidation()
    {
        $routeName = $this->route()->getName() ?? '';
        if (in_array($routeName, ['shop_timings.delete'])) {
            $this->merge([
                'hourSetId' => $this->route('hourSetId'),
            ]);
        }
    }

}
