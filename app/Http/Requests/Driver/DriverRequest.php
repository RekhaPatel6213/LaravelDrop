<?php

namespace App\Http\Requests\Driver;

use App\Models\Driver\DriverUser;
use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
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
            'profile_image' => 'nullable|sometimes|image|mimes:jpg,png,jpeg|max:2048',
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'email' => 'required|email|max:255|unique:driver_users,email,id',
            'phone' => 'required|numeric|digits:10|unique:driver_users,phone,id',
            'vehicle_type' => 'required|in:' . DriverUser::TRUCK . ',' . DriverUser::CAR . ','
                . DriverUser::MOTORCYCLE . ',' . DriverUser::BIKE . ',' . DriverUser::WALK,
        ];

        return $validationRules;
    }
}
