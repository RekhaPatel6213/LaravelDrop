<?php

namespace App\Models;

use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class GenericImport.
 *
 * @package namespace App\Models;
 */
class GenericImport extends Model implements Transformable
{
    use TransformableTrait;
    use DispensaryTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'data',
        'new_items',
        'existing_items',
        'total_price',
        'import_type',
        'user_id',
        'user_type',
        'dispensary_id',
        'status',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public const PENDING = 'PENDING';
    public const COMPLETED = 'COMPLETED';

    public function user()
    {
        return $this->morphTo();
    }

    public function scopeOfStatus($query, $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }
}
