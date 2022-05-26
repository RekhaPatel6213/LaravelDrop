<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;

class MessageBoxRequest extends FormRequest
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
        $validationRules =  [
            'title' => 'required|max:72',
            'description' => 'required',
        ];

        if (in_array($routeName, ['messages.save'])) {
            $validationRules['messageId'] = 'required|numeric|exists:message_boxes,id';
        }

        if (in_array($routeName, ['messages.delete'])) {
            $validationRules = [
                'messageId' => 'required|numeric|exists:message_boxes,id'
            ];
        }

        if ($routeName == 'messages.reorder') {
            $validationRules = [
                'message_ids' => 'required|array',
                'message_ids.*' => 'exists:message_boxes,id',
            ];
        }

        return $validationRules;
    }

    protected function prepareForValidation()
    {
        $routeName = $this->route()->getName() ?? '';
        if (in_array($routeName, ['messages.save', 'messages.delete'])) {
            $this->merge([
                'messageId' => $this->route('messageId'),
            ]);
        }
    }
}
