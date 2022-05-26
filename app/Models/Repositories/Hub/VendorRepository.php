<?php

namespace App\Models\Repositories\Hub;

use App\Models\Repositories\BaseRepository;
use App\Models\Hub\Vendor;

/**
 * Class VendorsRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Hub;
 */
class VendorRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Vendor::class;
    }

    /**
    * Vendor listing data
    *
    * @return mixed
    */
    public function getListingData(string $searchString, string $sortOn, string $sortOrder)
    {
        $query = $this->model->select('*');
        if (!empty($searchString)) {
            foreach (Vendor::SEARCH_FIELDS as $field) {
                $query->orWhere('vendors.' . $field, 'LIKE', '%' . $searchString . '%');
            }
        }
        return $query->orderBy($sortOn, $sortOrder)->get();
    }
}
