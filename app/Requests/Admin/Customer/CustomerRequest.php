<?php

namespace App\Http\Requests\Admin\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
        $customerId = isset($this->customerId) ? $this->customerId : null;
        $validationRules = [
            'email' => 'required|email|max:255',
            'phone' => 'required|numeric|digits:10',
            'birth_date' => 'required|max:12',
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
        ];

        /**
         * if method is patch or single field request update than add `sometimes`
         * as a first validation rule to check.
         */

        if ($this->isMethod('PATCH')) {
            foreach ($validationRules as $key => $validationRule) {
                $validationRules[$key] = "sometimes|{$validationRule}";
            }
            $validationRules['phone'] = 'sometimes|required|numeric|digits:10|unique:customers,phone,' . $customerId . ',id';
        }

        if (in_array($routeName, ['customers.update'])) {
            $validationRules['customerId'] = 'required|numeric|exists:dispensary_customers,id,dispensary_id,' . tenant('id');
        }

        if (in_array($routeName, ['customers.get', 'customers.delete'])) {
            $validationRules = [
                'customerId' => 'required|numeric|exists:customers,id'
            ];
        }

        if ($routeName == 'disp_customer.delete') {
            $validationRules = [
                'customerId' => 'required|numeric|exists:dispensary_customers,id,dispensary_id,' . tenant('id')
            ];
        }

        if (in_array($routeName, ['customers.import', 'customers.import_details'])) {
            $validationRules = [
                'previewId' => 'required|exists:generic_imports,id,dispensary_id,' . tenant('id')
            ];
        }
        
        return $validationRules;
    }

    protected function prepareForValidation()
    {
        $routeName = $this->route()->getName() ?? '';
        if (in_array($routeName, [
            'customers.update',
            'customers.get',
            'customers.change_status',
            'customers.delete',
            'disp_customer.delete'
        ])) {
            $this->merge([
                'customerId' => $this->route('customerId'),
            ]);
        }

        if (in_array($routeName, ['customers.import', 'customers.import_details'])) {
            $this->merge([
                'previewId' => $this->route('previewId'),
            ]);
        }


    }
}
