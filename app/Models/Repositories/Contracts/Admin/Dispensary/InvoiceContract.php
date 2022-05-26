<?php

namespace App\Models\Repositories\Contracts\Admin\Dispensary;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface InvoiceContract.
 *
 * @package namespace App\Models\Repositories\Contracts\Admin\Dispensary;
 */
interface InvoiceContract extends RepositoryInterface
{
    public function list(?string $search, string $sortOn, string $sortOrder);
}
