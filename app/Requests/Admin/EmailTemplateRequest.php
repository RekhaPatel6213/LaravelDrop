<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateRequest extends FormRequest
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
            'subject' => 'required',
            'text_template' => 'required',
            'html_template' => 'required',
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
