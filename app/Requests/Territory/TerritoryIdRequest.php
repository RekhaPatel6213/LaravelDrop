<?php

namespace App\Http\Requests\Territory;

use Illuminate\Foundation\Http\FormRequest;

class TerritoryIdRequest extends FormRequest
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
        $request['id'] = $this->route('id');
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
            'id' => 'required|numeric|exists:territories,id,dispensary_id,'.tenant('id')
        ];
    }
}
