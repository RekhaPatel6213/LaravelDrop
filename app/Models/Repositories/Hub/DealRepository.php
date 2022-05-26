<?php

namespace App\Models\Repositories\Hub;

use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Hub\DealInterface;
use App\Models\Hub\Deal;

/**
 * Class DealRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Hub;
 */
class DealRepository extends BaseRepository implements DealInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Deal::class;
    }

    public function getListingData(string $searchString, string $sortOn, string $sortOrder, string $status)
    {
        $query = $this->model->select('*');
        if (!empty($searchString)) {
            foreach (Deal::SEARCH_FIELDS as $field) {
                $query->orWhere('deals.' . $field, 'LIKE', '%' . $searchString . '%');
            }
        }
        $query->where('deals.status', $status);
        return $query->orderBy($sortOn, $sortOrder)->get();
    }


}
