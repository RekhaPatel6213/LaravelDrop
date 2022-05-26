<?php

namespace App\Http\Traits;

trait UserTrait
{
    public function getFullNameAttribute()
    {
        return trim(ucfirst($this->first_name).' '.ucfirst($this->last_name));
    }
}
