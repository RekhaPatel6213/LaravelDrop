<?php

namespace App\Models\Hub;

use Vanilo\Category\Models\Taxon as BaseTaxon;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Product\Models\ProductProxy;

class Category extends BaseTaxon
{
    public const INACTIVE = 'INACTIVE',
                 ACTIVE = 'ACTIVE',
                 GRAMS = 'GRAMS',
                 PREPACKAGED = 'PRE-PACKAGED',
                 UNITS = 'UNITS';

    public function dispensaryCategory()
    {
        return $this->hasOne('App\Models\Hub\DispensaryCategory', 'taxon_id', 'id');
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(
            ProductProxy::modelClass(),
            'model',
            'model_taxons',
            'taxon_id',
            'model_id'
        );
    }

    public function dealModels()
    {
        return $this->morphToMany(Deal::class, 'model','deal_models');
    }
}
