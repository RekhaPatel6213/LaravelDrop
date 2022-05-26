<?php

namespace App\Models\Repositories\Territory;

use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Territory\TaxesCostsSettingInterface;
use App\Models\Territory\TaxesCostsSetting;

/**
 * Class TaxesCostsSettingRepository.
 *
 * @package namespace App\Models\Repositories\Territory;
 */
class TaxesCostsSettingRepository extends BaseRepository implements TaxesCostsSettingInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TaxesCostsSetting::class;
    }

    public function getTaxListingData($territories, $sortOn, $sortOrder)
    {
        return $this->model
            ->whereIntegerInRaw('territory_id', $territories)
            ->orderBy($sortOn, $sortOrder)
            ->groupBy('territory_id')
            ->get();
    }
}
