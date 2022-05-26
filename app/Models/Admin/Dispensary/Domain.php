<?php

namespace App\Models\Admin\Dispensary;

use App\Http\Traits\DispensaryTrait;
use Stancl\Tenancy\Database\Models\Domain as BaseDomain;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Domain extends BaseDomain implements Transformable
{
    use DispensaryTrait, TransformableTrait;

    protected $fillable = [
        'domain',
        'dispensary_id',
        ];
}
