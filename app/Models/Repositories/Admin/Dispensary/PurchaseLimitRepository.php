<?php

namespace App\Models\Repositories\Admin\Dispensary;

use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Admin\Dispensary\PurchaseLimitInterface;
use App\Models\Admin\Dispensary\PurchaseLimit;

/**
 * Class PurchaseLimitRepository.
 *
 * @package namespace App\Models\Repositories\Admin\Dispensary;
 */
class PurchaseLimitRepository extends BaseRepository implements PurchaseLimitInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PurchaseLimit::class;
    }
}
