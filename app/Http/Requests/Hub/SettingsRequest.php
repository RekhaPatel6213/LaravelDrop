<?php

namespace App\Http\Requests\Hub;

use App\Settings\DispensarySettings;
use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
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
        $validationRules =  [];
        $type = $this->route('type');

        switch ($type) {
            case DispensarySettings::WEBSITE_SETTINGS:
                $validationRules = [
                    'homepage_title' => 'required|max:72',
                    'homepage_meta' => 'required|max:320',
                    'seo' => 'required|max:72',
                ];
                break;

            case DispensarySettings::ESTIMATED_DELIVERY:
                $validationRules = [
                    'estimated_to' => 'required|numeric|min:1|max:120',
                    'estimated_from' => 'required|numeric|min:1|max:120',
                ];
                break;

            case DispensarySettings::CUSTOMER_VERIFY:
                $validationRules = [
                    'recreational.age' => 'required|numeric|min:16|max:25',
                    'medical.age' => 'required|numeric|min:16|max:25',
                ];
                break;

            case DispensarySettings::PAYMENT_OPTIONS:
                $validationRules = [
                    'debit_card_fee' => 'required|numeric',
                ];
                break;

            case DispensarySettings::ORDER_FEES:
                $validationRules = [
                    'service_fee_amount' => 'required|numeric',
                    'additional_service_fee_amount' => 'required|numeric',
                ];
                break;

            default:
                break;
        }

        return $validationRules;
    }
}
