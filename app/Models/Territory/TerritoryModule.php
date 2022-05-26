<?php

namespace App\Models\Territory;

use App\Models\Driver\DriverUser;
use App\Models\Location\Location;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Models\Hub\Inventory;

/**
 * Class TerritoryModule.
 *
 * @package namespace App\Models\Territory;
 */
class TerritoryModule extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'territory_id',
        'module_id',
        'module_type',
        'delivery_fee',
        'minimum_delivery_amount',
        'minimum_for_free_delivery',
    ];

    public const LOCATION = 'Location';
    public const DRIVER = 'Driver';
    public const DISPENSARYUSER = 'dispensary_user';
    public const REPOSITORY = [
        self::LOCATION => 'location',
        self::DRIVER => 'driver',
        self::DISPENSARYUSER => 'dispensaryUser',
    ];

    public function getRelationshipToPrimaryModel(): string
    {
        return 'territory';
    }

    public function module()
    {
        return $this->morphTo();
    }

    public function territory()
    {
        return $this->belongsTo(Territory::class, 'territory_id');
    }

    public function locations()
    {
        return $this->morphedByMany(Location::class, 'module','territory_modules');
    }

    public function drivers()
    {
        return $this->morphedByMany(DriverUser::class, 'module','territory_modules');
    }

    public function inventory()
    {
        return $this->morphOne(Inventory::class, 'module','territory_modules');
    }

    public function scopeNotTerritoryId($query, $territoryId)
    {
        return $territoryId ? $query->where('territory_id', '!=', $territoryId) : $query;
    }

    public function scopeOfModelType($query, $modelType)
    {
        return $modelType ? $query->where('module_type', $modelType) : $query;
    }
}
