<?php

namespace App\Models\Repositories\Contracts\Admin\Customer;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface DispensaryCustomerRepository.
 *
 * @package namespace App\Models\Repositories\Contracts\Admin\Customer;
 */
interface DispensaryCustomerRepository extends RepositoryInterface
{
    public function isRecordExists(int $customerId);

    public function getUniqueRecord(int $customerId);
}
