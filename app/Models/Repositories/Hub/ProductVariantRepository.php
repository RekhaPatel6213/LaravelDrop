<?php

namespace App\Models\Repositories\Hub;

use App\Models\Hub\ProductVariant;
use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Hub\ProductVariantInterface;

/**
 * Class PromoCodeRepository.
 *
 * @package namespace App\Models\Repositories\Hub;
 */
class ProductVariantRepository extends BaseRepository implements ProductVariantInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProductVariant::class;
    }

}
