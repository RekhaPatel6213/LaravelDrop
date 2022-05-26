<?php

namespace App\Models\Hub;

use Konekt\Address\Models\Address as BaseAddress;
use Vanilo\Contracts\Address as AddressContract;
use Vanilo\Support\Traits\AddressModel;

class Address extends BaseAddress implements AddressContract
{
    use AddressModel;
}
