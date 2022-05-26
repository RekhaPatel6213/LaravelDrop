<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Hub\Banner;
use Illuminate\Validation\Rule;
use App\Http\Traits\DispensaryTrait;
use App\Rules\Hub\BetweenNumArray;


class BannerRequest extends FormRequest
{
    use DispensaryTrait;

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
        if($this->route('bannerId')){
            $request['bannerId'] = $this->route('bannerId');
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
            'bannerId' => 'sometimes|exists:banners,id,dispensary_id,'.tenant('id'),
            'type' => ['required', Rule::in(Banner::MESSAGE, Banner::GALLERY)],
            'banner_image' => 'required_if:type,'.Banner::GALLERY.'|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|max:35',
            'description' => 'required',
            'redirect_type' => ['required',config('constants.REDIRECT_TYPE')],
            'redirect_detail' => 'required_with:redirect_type|string',
            'frequency' => ['required', Rule::in(Banner::RECURRING, Banner::LIMITED)],
            'from_time' => 'required_if:type,'.Banner::MESSAGE.'|nullable|date_format: "H:i"',
            'to_time' => 'required_if:type,'.Banner::MESSAGE.'|nullable|date_format: "H:i|after:from_time"',
            'start_date' => 'required|date_format:"Y-m-d"',
            'end_date' => 'required_if:frequency,'.Banner::LIMITED.'|nullable|after_or_equal:start_date|date_format:"Y-m-d"',
            'days' => ['required', new BetweenNumArray ],
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
                'bannerId' => 'numeric|exists:banners,id,dispensary_id,'.tenant('id'),
            ];
        }
        return $validationRules;
    }
}
