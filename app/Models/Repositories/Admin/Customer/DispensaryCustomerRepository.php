<?php

namespace App\Models\Repositories\Admin\Customer;

use App\Models\Admin\Customer\DispensaryCustomer;
use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Admin\Customer\DispensaryCustomerRepository as DispensaryCustomerInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class DispensaryCustomerTempRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Admin\Customer;
 */
class DispensaryCustomerRepository extends BaseRepository implements DispensaryCustomerInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DispensaryCustomer::class;
    }

    public function isRecordExists(int $customerId)
    {
        return $this->model->where('id', $customerId)->exists();
    }

    public function getUniqueRecord(int $customerId)
    {
        return $this->model->find($customerId);
    }

    public function getDispensaryCustomer(int $customerId)
    {
        return $this->model->where('customer_id', $customerId)->first();
    }

    public function getListingData(string $searchString, string $sortOn, string $sortOrder, string $status)
    {
        $query = $this->model->select(
            'dispensary_customers.id as id',
            DB::raw('CONCAT(dispensary_customers.first_name, " ", dispensary_customers.last_name) as name'),
            'customers.email',
            'customers.phone',
            'dispensary_customers.verify_status'
        )->join('customers', 'dispensary_customers.customer_id', '=', 'customers.id');

        if (!empty($searchString)) {
            foreach (DispensaryCustomer::SEARCH_FIELDS as $field) {
                $query->orWhere($field, 'LIKE', '%' . $searchString . '%');
            }
            $query->orWhere('customers.email', 'LIKE', '%' . $searchString . '%');
            $query->orWhere('customers.phone', 'LIKE', '%' . $searchString . '%');
        }
        $query = $status != '' ? $query->where('dispensary_customers.verify_status', $status) : $query;
        return $query->orderBy($sortOn, $sortOrder)->get();
    }

    public function getExportCustomersData()
    {
        return $this->model->select(
            'dispensary_customers.id',
            DB::raw('CONCAT(first_name, " ", last_name) as CustomerName'),
            'customers.email as email',
            'customers.phone as phone',
            'verify_status'
        )->join('customers', 'dispensary_customers.customer_id', '=', 'customers.id')->get();
    }

    public function getSmsOptedCustomersCount()
    {
        return $this->model->join('customers', 'customers.id', '=', 'dispensary_customers.customer_id')
            ->where('dispensary_customers.sms_enabled', true)
            ->where('dispensary_customers.status', DispensaryCustomer::ACTIVE)->count();
    }
}
