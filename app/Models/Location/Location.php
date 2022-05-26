<?php

namespace App\Models\Location;

use App\Models\Territory\Territory;
use App\Models\Territory\TerritoryModule;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Location.
 *
 * @package namespace App\Models\Location;
 */
class Location extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'zip_code',
        'city',
        'state',
        'short_state',
        'county',
        'country',
        'lat',
        'lng',
    ];

    public function territoryModules()
    {
        return $this->morphToMany(Territory::class, 'module','territory_modules');
    }
}
