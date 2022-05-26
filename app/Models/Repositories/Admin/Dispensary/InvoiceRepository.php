<?php

namespace App\Models\Repositories\Admin\Dispensary;

use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Admin\Dispensary\InvoiceContract;
use App\Models\Admin\Dispensary\Invoice;

/**
 * Class InvoiceRepository.
 *
 * @package namespace App\Models\Repositories\Admin\Dispensary;
 */
class InvoiceRepository extends BaseRepository implements InvoiceContract
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Invoice::class;
    }

    public function list(?string $search, string $sortOn, string $sortOrder)
    {
       return $this->getQueryBuilder($this->model, $search, $sortOn, $sortOrder)->get();
    }
}
