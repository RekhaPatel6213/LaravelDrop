<?php

namespace App\Models\Repositories\Admin\Dispensary;

use App\Models\Admin\Dispensary\DropOffOption;
use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Admin\Dispensary\DropOffOptionRepository as DropOffOptionInterface;

/**
 * Class DropOffOptionRepository.
 *
 * @package namespace App\Models\Repositories\Admin\Dispensary;
 */
class DropOffOptionRepository extends BaseRepository implements DropOffOptionInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DropOffOption::class;
    }
}
