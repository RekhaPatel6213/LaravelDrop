<?php

namespace App\Models\Territory;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Stancl\Tenancy\Database\Concerns\BelongsToPrimaryModel;

/**
 * Class TerritoryGeoPoint.
 *
 * @package namespace App\Models\Territory;
 */
class TerritoryGeoPoint extends Model implements Transformable
{
    use TransformableTrait;
    use BelongsToPrimaryModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'territory_id',
        'geo_points'
    ];

    protected $casts = [
        'geo_points' => 'array'
    ];

    public $timestamps = false;

    public function getRelationshipToPrimaryModel(): string
    {
        return 'territory';
    }

    public function territory()
    {
        return $this->belongsTo(Territory::class, 'territory_id');
    }
}
