<?php

namespace App\Models\Repositories\Hub;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Hub\Reward;

/**
 * Class RewardRepository.
 *
 * @package namespace App\Models\Repositories\Hub;
 */
class RewardRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Reward::class;
    }

    /**
     * Reward listing data
     *
     * @return mixed
     */
    public function getListingData(string $searchString, string $sortOn, string $sortOrder)
    {
        $query = $this->model->select('*');
        if (!empty($searchString)) {
            foreach (Reward::SEARCH_FIELDS as $field) {
                $query->orWhere('rewards.' . $field, 'LIKE', '%' . $searchString . '%');
            }
        }
        return $query->orderBy($sortOn, $sortOrder)->get();
    }
}