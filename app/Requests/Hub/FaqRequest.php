<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
        return [
            'faqId' => 'required|exists:faqs,id,dispensary_id,'.tenant('id')
        ];
    }
}
