<?php

namespace App\Models\Repositories\Contracts\Admin\Customer;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CustomerRepository.
 *
 * @package namespace App\Models\Repositories\Contracts\Admin\Customer;
 */
interface CustomerRepository extends RepositoryInterface
{
    public function getListingData(string $searchString, string $sortOn, string $sortOrder, string $status);
}
