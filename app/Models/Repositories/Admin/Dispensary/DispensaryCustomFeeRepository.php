<?php

namespace App\Models\Repositories\Admin\Dispensary;

use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Admin\Dispensary\DispensaryCustomFeeInterface;
use App\Models\Admin\Dispensary\DispensaryCustomFee;

/**
 * Class DispensaryCustomFeeRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Admin\Dispensary;
 */
class DispensaryCustomFeeRepository extends BaseRepository implements DispensaryCustomFeeInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DispensaryCustomFee::class;
    }
}
