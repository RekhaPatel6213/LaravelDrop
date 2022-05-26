<?php

namespace App\Rules\Hub;

use Illuminate\Contracts\Validation\Rule;

class BetweenNumArray implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ('array' !== gettype($value)) {
            $value = explode(',', $value );
        }
        $start = -1;
        $end = 6;
        foreach($value as $num){
            if (($num < $start) || ($num > $end) || !is_numeric($num)){
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be numeric value between -1, 6';
    }
}
