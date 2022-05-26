<?php

namespace App\Http\Requests\Driver;

use App\Models\Driver\DriverUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DriverUpdateRequest extends FormRequest
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
        $driverId = isset($this->driverId) ? $this->driverId : null;
        $routeName = $this->route()->getName() ?? '';

        $validationRules = [
            'profile_image' => 'nullable|sometimes|image|mimes:jpg,png,jpeg|max:2048',
            'first_name' => 'sometimes|max:50',
            'last_name' => 'sometimes|max:50',
            'email' => 'sometimes|email|max:255|unique:driver_users,email,'. $driverId . ',id',
            'phone' => 'sometimes|numeric|digits:10|unique:driver_users,phone,'. $driverId . ',id',
            'vehicle_type' => 'required|in:' . DriverUser::TRUCK . ',' . DriverUser::CAR . ','
                . DriverUser::MOTORCYCLE . ',' . DriverUser::BIKE . ',' . DriverUser::WALK,
        ];

        if (in_array($routeName, ['drivers.update', 'drivers.patch'])) {
            $validationRules['driverId'] = [
                'required',
                'numeric',
                Rule::exists('driver_users', 'id')->where('dispensary_id', tenant('id'))
            ];
        }

        if (in_array($routeName, ['drivers.delete', 'drivers.get'])) {
            $validationRules =  [
                'driverId' => [
                    'required',
                    'numeric',
                    Rule::exists('driver_users', 'id')->where('dispensary_id', tenant('id'))
                ]
            ];
        }


        return $validationRules;
    }


    protected function prepareForValidation()
    {
        $routeName = $this->route()->getName() ?? '';
        if (in_array($routeName, ['drivers.delete', 'drivers.update', 'drivers.patch', 'drivers.get'])) {
            $this->merge([
                'driverId' => $this->route('driverId'),
            ]);
        }
    }
}
