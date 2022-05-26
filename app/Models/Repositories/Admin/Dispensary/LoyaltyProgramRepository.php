<?php

namespace App\Models\Repositories\Admin\Dispensary;

use App\Models\Admin\Dispensary\LoyaltyProgram;
use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Admin\Dispensary\LoyaltyProgramRepository as LoyaltyProgramInterface;

/**
 * Class LoyaltyProgramRepository.
 *
 * @package namespace App\Models\Repositories\Admin\Dispensary;
 */
class LoyaltyProgramRepository extends BaseRepository implements LoyaltyProgramInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return LoyaltyProgram::class;
    }

    public function getListingData(string $sortOn, string $sortOrder)
    {
        $query = $this->model->select('*');
        return $query->orderBy($sortOn, $sortOrder)->get();
    }

    public function getDefaults()
    {
        return $this->model->default()->get();
    }
}
