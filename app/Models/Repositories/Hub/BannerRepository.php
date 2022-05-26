<?php

namespace App\Models\Repositories\Hub;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Repositories\Contracts\Hub\BannerInterface;
use App\Models\Hub\Banner;

/**
 * Class BannerRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Hub;
 */
class BannerRepository extends BaseRepository implements BannerInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Banner::class;
    }

    /**
     * Banner listing data
     *
     * @return mixed
     */
    public function getListingData(string $searchString, string $sortOn, string $sortOrder)
    {
        $query = $this->model->select('*');
        if (!empty($searchString)) {
            foreach (Banner::SEARCH_FIELDS as $field) {
                $query->orWhere('banners.' . $field, 'LIKE', '%' . $searchString . '%');
            }
        }
        return $query->orderBy($sortOn, $sortOrder)->get();
    }
}
