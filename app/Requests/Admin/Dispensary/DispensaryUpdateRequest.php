<?php

namespace App\Http\Requests\Admin\Dispensary;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin\Dispensary\Dispensary;

class DispensaryUpdateRequest extends FormRequest
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
        $dispensaryId = tenant('id');

        $validationRules = [
            'logo' => 'nullable|sometimes|image|mimes:jpg,png,jpeg|max:2048',
            'header_logo' => 'nullable|sometimes|image|mimes:jpg,png,jpeg|max:2048',
            'app_icon' => 'nullable|sometimes|image|mimes:jpg,png,jpeg|max:2048',
            'name' => 'sometimes|max:50',
            'email' => 'sometimes|email|max:255|unique:dispensaries,email,'. $dispensaryId . ',id',
            'phone' => 'sometimes|numeric|digits:10',
            'address' => 'sometimes|max:255',
            'domain' => 'sometimes|max:255|unique:domains,domain,'. $dispensaryId . ',dispensary_id',
            'own_domain' => 'sometimes|max:255',
            'first_name' => 'sometimes|max:50',
            'last_name' => 'sometimes|max:50',
            'contact_email' => 'sometimes|max:255',
            'contact_phone' => 'sometimes|max:255',
            'admin_user_id' => 'sometimes|numeric|exists:admin_users,id',
            'bitly_link' => 'sometimes|max:255',
            'setup_fee' => 'sometimes|numeric',
            'services' => 'sometimes|max:8',
            'billing_prompt' => 'sometimes|in:'.Dispensary::MANUALLY_BILLED.','.Dispensary::CARD,
            'service_fee_enabled' => 'sometimes|in:'.Dispensary::ENABLED.','.Dispensary::DISABLED,
            'service_fee_amount' => 'sometimes|numeric',
            'subscription_type' => 'sometimes|max:255',
            'app_name' => 'sometimes|max:100',
            'website' => 'sometimes|max:255',
            'state_licence' => 'sometimes|max:255',
            'timezone' => 'sometimes|max:100',
            'product_notes' => 'sometimes|in:YES,NO',
            'dispatch_minimum' => 'sometimes|in:YES,NO',
            'few_left_banner' => 'sometimes|in:YES,NO',
            'app_color' => 'sometimes|max:7',
        ];


        return $validationRules;
    }
}
