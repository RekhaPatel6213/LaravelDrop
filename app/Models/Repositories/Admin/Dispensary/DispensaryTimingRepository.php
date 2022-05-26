<?php

namespace App\Models\Repositories\Admin\Dispensary;

use App\Models\Admin\Dispensary\DispensaryTiming;
use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Admin\Dispensary\DispensaryTimingRepository as DispensaryTimingInterface;

/**
 * Class DispensaryTimingRepository.
 *
 * @package namespace App\Models\Repositories\Admin\Dispensary;
 */
class DispensaryTimingRepository extends BaseRepository implements DispensaryTimingInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DispensaryTiming::class;
    }
}
