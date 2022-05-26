<?php

namespace App\Models\Repositories\Admin\Dispensary;

use App\Models\Admin\Dispensary\DispensaryPaymentMethod;
use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Admin\Dispensary\DispensaryPaymentMethodRepository as DispensaryPaymentMethodInterface;

/**
 * Class DispensaryPaymentMethodRepository.
 *
 * @package namespace App\Models\Repositories\Admin\Dispensary;
 */
class DispensaryPaymentMethodRepository extends BaseRepository implements DispensaryPaymentMethodInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DispensaryPaymentMethod::class;
    }
}
