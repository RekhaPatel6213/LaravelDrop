<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Hub\SMSCampaign;
use Illuminate\Validation\Rule;
use App\Models\Repositories\Admin\Customer\CustomerRepository;


class SMSCampaignRequest extends FormRequest
{
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository) {
        $this->customerRepository = $customerRepository;
    }
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
        if($this->route('smscampaignId')){
            $request['smscampaignId'] = $this->route('smscampaignId');
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
            'smscampaignId' => 'sometimes|numeric',
            'patient_type' => ['required',Rule::in(config('constants.PATIENT_TYPE'))],
            'segmentation' => 'required',
            'message' => 'required',
            'total_customer' => 'required|numeric|gt:0',
            'type_scheduled' => ['required',Rule::in(SMSCampaign::TYPE_SCHEDULE)],
            'schedule_date' => 'required_if:type_scheduled,'.SMSCampaign::TYPE_SCHEDULE['SEND_LATER'].'|nullable|after_or_equal:'.Date('Y-m-d'),
            'schedule_time.*' => 'required_if:type_scheduled,'.SMSCampaign::TYPE_SCHEDULE['SEND_LATER'].'|nullable|date_format:H:i'
        ];

        /**
         * if method is patch or single field request update than add `sometimes`
         * as a first validation rule to check.
         */
        if ($this->isMethod('PATCH')) {
            foreach ($validationRules as $key => $validationRule) {
                if (gettype($validationRule) !== 'array') {
                    $validationRules[$key] = strpos($validationRule, 'sometimes') === false ? "sometimes|{$validationRule}" : "{$validationRule}";
                }
                if (gettype($validationRule) === 'array') {
                    $validationRules[$key] = array_merge($validationRule, ['sometimes']);
                }
            }
        }
        return $validationRules;
    }
}
