<?php

namespace App\Http\Requests\Hub;

use App\Models\Admin\Dispensary\LoyaltyProgram;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoyaltyProgramRequest extends FormRequest
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
        $validationPostRules =  [
            'name' => 'required|max:255',
            'points' => 'required|numeric|max:2147483647',
        ];

        $validationRules =  [
            'start_time' => 'sometimes|required|date_format: "H:i"',
            'end_time' => 'sometimes|required|date_format: "H:i"',
            'type' => 'sometimes|required|in:'. LoyaltyProgram::STANDARD_LOYALTY . ',' . LoyaltyProgram::NEW_LOYALTY
                . ',' . LoyaltyProgram::BIRTHDAY. ',' . LoyaltyProgram::REFERRAL
                . ',' . LoyaltyProgram::TIME_BASED,
            'schedule' => 'sometimes|required|in:'. LoyaltyProgram::WEEKLY . ',' . LoyaltyProgram::BI_WEEKLY
                . ',' . LoyaltyProgram::MONTHLY. ',' . LoyaltyProgram::MANUALLY,
            'active_days' => [
                'sometimes',
                function ($attribute, $value, $fail) {
                    $valueArr = explode(',', $value);
                    $daysArr = [0,1,2,3,4,5,6];
                    foreach ($valueArr as $item) {
                        if (!in_array($item, $daysArr)) {
                            $fail('The '.$attribute.' for value ' . $item . ' is invalid.');
                        }
                    }
                },
            ],
            'status' => 'sometimes|in:'. LoyaltyProgram::ACTIVE . ',' . LoyaltyProgram::DISABLED,
            'custom_message' => 'sometimes',
        ];

        if ($routeName == 'loyalty_program.patch') {
            foreach ($validationPostRules as $key => $validationRule) {
                $validationPostRules[$key] = "sometimes|{$validationRule}";
            }
            $validationPostRules['programId'] = 'required|numeric|exists:loyalty_programs,id';
            array_push($validationRules, $validationPostRules);
        }



        if ($routeName == 'loyalty_program.update') {
            $validationRules['programId'] = 'required|numeric|exists:loyalty_programs,id';
        }

        if (in_array($routeName, ['loyalty_program.delete', 'loyalty_program.get_program'])) {
            $validationRules = [
                'programId' => 'required|numeric|exists:loyalty_programs,id'
            ];
        }

        if ($routeName == 'loyalty_program.update_defaults') {
            $validationRules = [
                'defaults.*.id' => [
                    'required',
                     Rule::exists('loyalty_programs', 'id')->where('is_default', true)
                ],
                'defaults.*.points' => 'required|numeric',
                'defaults.*.status' => 'required|in:'. LoyaltyProgram::ACTIVE . ',' . LoyaltyProgram::DISABLED,
            ];
        }

        return $validationRules;
    }


    protected function prepareForValidation()
    {
        $routeName = $this->route()->getName() ?? '';
        if (in_array($routeName, ['loyalty_program.delete', 'loyalty_program.get_program', 'loyalty_program.patch', 'loyalty_program.update'])) {
            $this->merge([
                'programId' => $this->route('programId'),
            ]);
        }
    }
}
