<?php

namespace App\Models\Repositories\Location;

use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Location\LocationInterface;
use App\Models\Location\Location;
use Illuminate\Support\Facades\DB;

/**
 * Class LocationRepository.
 *
 * @package namespace App\Models\Repositories\Location;
 */
class LocationRepository extends BaseRepository implements LocationInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Location::class;
    }


    public function getZipcodesByLocationIds(array $locationIds)
    {
        $query = $this->model->select(DB::raw('group_concat(zip_code) as zipcodes'))
            ->whereIntegerInRaw('id', $locationIds)->first();
        return $query->zipcodes ? $query->zipcodes : '';
    }

    public function getZipCode(int $locationId)
    {
        $query = $this->model->find($locationId);
        return $query->zip_code;
    }
}
