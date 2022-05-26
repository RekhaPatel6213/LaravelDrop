<?php

namespace App\Models\Repositories\Hub;

use App\Models\Hub\Category;
use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Hub\CategotyInterface;

/**
 * Class PromoCodeRepository.
 *
 * @package namespace App\Models\Repositories\Hub;
 */
class CategoryRepository extends BaseRepository implements CategotyInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
    }

}
