<?php

namespace App\Models\Repositories\Admin\Dispensary;

use App\Models\Admin\Dispensary\DispensaryHourSet;
use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Admin\Dispensary\DispensaryHourSetRepository as DispensaryHourSetInterface;

/**
 * Class DispensaryHourSetRepository.
 *
 * @package namespace App\Models\Repositories\Admin\Dispensary;
 */
class DispensaryHourSetRepository extends BaseRepository implements DispensaryHourSetInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DispensaryHourSet::class;
    }
}
