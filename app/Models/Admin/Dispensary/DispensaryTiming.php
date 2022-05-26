<?php

namespace App\Models\Admin\Dispensary;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToPrimaryModel;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class DispensaryTiming extends Model implements Transformable
{
    use BelongsToPrimaryModel, TransformableTrait;

    protected $fillable = [
        'dispensary_hour_set_id',
        'day',
        'from_time',
        'to_time',
        ];

    public function getRelationshipToPrimaryModel(): string
    {
        return 'DispensaryHourSet';
    }

    public function dispensaryHourSet()
    {
        return $this->belongsTo(DispensaryHourSet::class);
    }
}
