<?php

namespace App\Models\Repositories\Admin\Customer;

use App\Models\Admin\Customer\Customer;
use App\Models\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Repositories\Contracts\Admin\Customer\CustomerRepository as CustomerInterface;

/**
 * Class CustomercRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Admin\Customer;
 */
class CustomerRepository extends BaseRepository implements CustomerInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Customer::class;
    }

    public function getListingData(string $searchString, string $sortOn, string $sortOrder, string $status)
    {
        $query = $this->model->select(
            'customers.id',
            'customers.email',
            'customers.phone',
            'dc.disps as dispensaries',
            'dc.full_names'
        );
        $query->leftJoin(
            DB::raw('(
        select 
            customer_id,
            GROUP_CONCAT(CONCAT(first_name, " ", last_name)) as full_names,
            GROUP_CONCAT(dispensary_id) as disps
          from dispensary_customers
          group by customer_id ) dc'),
            function ($join) {
                $join->on('dc.customer_id', '=', 'customers.id');
            }
        );
        if (!empty($searchString)) {
            foreach (Customer::SEARCH_FIELDS as $field) {
                $query->orWhere($field, 'LIKE', '%' . $searchString . '%');
            }
            $query->orWhereRaw('dc.full_names LIKE \'%' . $searchString . '%\'');
        }
        $query->where('customers.status', $status);
        return $query->orderBy($sortOn, $sortOrder)->get();
    }
}
