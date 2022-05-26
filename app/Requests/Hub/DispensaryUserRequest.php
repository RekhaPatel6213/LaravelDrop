<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin\Dispensary\DispensaryUser;
use Illuminate\Validation\Rule;

class DispensaryUserRequest extends FormRequest
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
        if($this->route('dispensaryUserId')){
            $request['dispensaryUserId'] = $this->route('dispensaryUserId');
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
        $dispensaryUserId = isset($this->dispensaryUserId) ? $this->dispensaryUserId : null;

        $validationRules = [
            'dispensaryUserId' => 'sometimes|numeric|exists:dispensary_users,id,dispensary_id,'.tenant('id'),
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'email' => 'required|email|max:255|unique:dispensary_users,email,'.$dispensaryUserId.',id,deleted_at,NULL',
            'phone' => 'required|numeric|digits:10|unique:dispensary_users,phone,'.$dispensaryUserId.',id,deleted_at,NULL',
            'staff_role' => 'string',
            'role' => ['required',Rule::in(DispensaryUser::ALL, DispensaryUser::HUB, DispensaryUser::DISPATCH)],
            'territory_ids' => 'required|array',
            'territory_ids.*' => 'numeric|exists:territories,id,dispensary_id,'.tenant('id'),
            'access' => 'array',
            'access.*' => 'numeric|exists:permissions,id'
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

        if ($this->isMethod('DELETE')) {
            $validationRules = [
                'dispensaryUserId' => 'numeric|exists:dispensary_users,id,dispensary_id,'.tenant('id').',is_owner,'.DispensaryUser::NO,
            ];
        }

        return $validationRules;
    }
}
