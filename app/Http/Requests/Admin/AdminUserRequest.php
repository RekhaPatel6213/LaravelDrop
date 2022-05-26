<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
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
        if($this->route('adminId')){
            $request['adminId'] = $this->route('adminId');
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
        $adminId = isset($this->adminId) ? $this->adminId : null;

        $validationRules = [
            'adminId' => 'sometimes|exists:admin_users,id,deleted_at,NULL',
            'first_name' => 'required|max:35',
            'last_name' => 'required|max:35',
            'phone_number' => 'required|min:11|numeric',
            'email' => 'required|email|max:255|unique:admin_users,email,'.$adminId.',id,deleted_at,NULL',
            'role' => 'required|exists:roles,name',
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
