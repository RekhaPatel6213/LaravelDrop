<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;

class VendorCreateRequest extends FormRequest
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

    public function all($key = null)
    {
        $request = parent::all($key);
        if($this->route('vendorId')){
            $request['vendorId'] = $this->route('vendorId');
        }
        return $request;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validationRules = [
            'vendorId' => 'sometimes|numeric|exists:vendors,id',
            'name' => 'required|max:35',
            'phone' => 'required|min:11|numeric',
            'email' => 'required|email|max:255|unique:vendors'
        ];

        /**
         * if method is patch or single field request update than add `sometimes`
         * as a first validation rule to check.
         */
        if ($this->isMethod('PATCH')) {
            foreach ($validationRules as $key => $validationRule) {
                $validationRules[$key] = "sometimes|{$validationRule}";
            }
        }
        return $validationRules;
    }
}
