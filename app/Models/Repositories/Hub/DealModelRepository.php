<?php

namespace App\Models\Repositories\Hub;

use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Hub\DealModelInterface;
use App\Models\Hub\DealModel;

/**
 * Class DealModelRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Hub;
 */
class DealModelRepository extends BaseRepository implements DealModelInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DealModel::class;
    }

}
