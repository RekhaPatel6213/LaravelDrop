<?php

namespace App\Models\Brand;

use App\Http\Traits\DispensaryTrait;
use App\Models\Hub\Deal;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Brand.
 *
 * @package namespace App\Models\Brand;
 */
class Brand extends Model implements Transformable
{
    use TransformableTrait, DispensaryTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dispensary_id',
        'name'
    ];

    public function dealModels()
    {
        return $this->morphToMany(Deal::class, 'model','deal_models');
    }

}
