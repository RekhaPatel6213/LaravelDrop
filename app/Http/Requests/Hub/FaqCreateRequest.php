<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;

class FaqCreateRequest extends FormRequest
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
        if($this->route('faqId')){
            $request['faqId'] = $this->route('faqId');
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
            'faqId' => 'sometimes|numeric|exists:faqs,id,dispensary_id,'.tenant('id'),
            'question' => 'required|max:100|unique:faqs,question,NULL,id,dispensary_id,'.tenant('id'),
            'answer' => 'required'
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
