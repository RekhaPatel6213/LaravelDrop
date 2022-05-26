<?php

namespace App\Models\Repositories\Brand;

use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Brand\BrandInterface;
use App\Models\Brand\Brand;

/**
 * Class BrandRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Brand;
 */
class BrandRepository extends BaseRepository implements BrandInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Brand::class;
    }

    public function brandList($search = null)
    {
        return $search !== null ? $this->model->where('name', 'LIKE', '%' . $search . '%')->get() : $this->model->get();
    }
}
