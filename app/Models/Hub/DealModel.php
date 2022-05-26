<?php

namespace App\Models\Hub;

use App\Models\Brand\Brand;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class DealModel.
 *
 * @package namespace App\Models\Hub;
 */
class DealModel extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'deal_id',
        'model_id',
        'model_type',
        'type',
        'sub_type',
    ];

    public function getRelationshipToPrimaryModel(): string
    {
        return 'deal';
    }

    public function model()
    {
        return $this->morphTo();
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deal_id');
    }

    public function brands()
    {
        return $this->morphedByMany(Brand::class, 'model','deal_models');
    }

    public function products()
    {
        return $this->morphedByMany(Product::class, 'model','deal_models');
    }

    public function productVariants()
    {
        return $this->morphedByMany(ProductVariant::class, 'model','deal_models');
    }

    public function categories()
    {
        return $this->morphedByMany(Category::class, 'model','deal_models');
    }

}
