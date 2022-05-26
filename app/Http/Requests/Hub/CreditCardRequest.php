<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class CreditCardRequest extends FormRequest
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
        if($this->route('creditCardId')){
            $request['creditCardId'] = $this->route('creditCardId');
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
        $validationRules = [];
        $routeName = Route::currentRouteName();
        switch ($routeName) {
            case 'card.create':
                $validationRules = [
                    'stripe_token' => 'required|string',
                    'name' => 'required|string',
                    'email' => 'required|email'
                ];
                break;
            case 'card.default':
            case 'card.delete':
                $validationRules = [
                    'creditCardId' => 'required|numeric|exists:credit_cards,id,dispensary_id,'.tenant('id').',deleted_at,NULL'
                ];
                break;
            default:
                break;
        }

        return $validationRules;
    }
}
