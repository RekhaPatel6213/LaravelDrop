<?php

namespace App\Models\Repositories\Admin\Dispensary;

use App\Models\Admin\Dispensary\Domain;
use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Admin\Dispensary\DomainRepository as DomainInterface;

/**
 * Class DomainRepository.
 *
 * @package namespace App\Models\Repositories\Admin\Dispensary;
 */
class DomainRepository extends BaseRepository implements DomainInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Domain::class;
    }
}
