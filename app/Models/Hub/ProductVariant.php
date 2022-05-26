<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vanilo\Category\Models\TaxonomyProxy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(schema="VariantSortsOn", type="array",
 *     @OA\Items(type="string", enum={"Flower", "Pre-Packaged"})
 * )
 */
class ProductVariant extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(TaxonomyProxy::modelClass(), 'taxonomy_id');
    }

    public function productDetail()
    {
        return $this->hasOne(ProductDetail::class, 'variant_id', 'id');
    }

    public function dealModels()
    {
        return $this->morphToMany(Deal::class, 'model','deal_models');
    }
    
    public function scopeOfName($query, $name)
    {
        return $name ? $query->where('name', $name) : $query;
    }

    public function scopeOfAttribute($query, $attribute)
    {
        return $attribute ? $query->where('attribute', $attribute) : $query;
    }
}
