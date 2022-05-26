<?php

namespace App\Http\Requests\Admin\Dispensary;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin\Dispensary\Dispensary;

class DispensaryRequest extends FormRequest
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
            'logo' => 'nullable|sometimes|image|mimes:jpg,png,jpeg|max:2048',
            'name' => 'required|max:50',
            'email' => 'required|email|max:255|unique:dispensaries,email,id',
            'phone' => 'required|numeric|digits:10',
            'address' => 'required|max:255',
            'domain' => 'required|max:255|unique:domains',
            'own_domain' => 'required|max:255',
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'contact_email' => 'required|max:255',
            'contact_phone' => 'required|max:255',
            'admin_user_id' => 'required|numeric|exists:admin_users,id',
            'bitly_link' => 'required|max:255',
            'setup_fee' => 'required|numeric',
            'services' => 'required|max:8',
            'billing_prompt' => 'required|in:'.Dispensary::MANUALLY_BILLED.','.Dispensary::CARD,
            'service_fee_enabled' => 'required|in:'.Dispensary::ENABLED.','.Dispensary::DISABLED,
            'service_fee_amount' => 'required|numeric',
            'subscription_type' => 'required|max:255',
        ];

        /**
         * if method is patch or single field request update than add `sometimes`
         * as a first validation rule to check.
         */

        if ($this->isMethod('PATCH')) {
            foreach ($validationRules as $key => $validationRule) {
                $validationRules[$key] = "sometimes|{$validationRule}";
            }
            $validationRules['email'] = 'sometimes|email|max:255';
        }
        
        return $validationRules;
    }
}
