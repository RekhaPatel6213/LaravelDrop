<?php

namespace App\Models\Repositories\Admin\Dispensary;

use App\Models\Admin\Dispensary\Dispensary;
use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Admin\Dispensary\DispensaryRepository as DispensaryInterface;
use App\Models\Admin\Dispensary\DispensaryUser;
use Illuminate\Support\Facades\DB;

/**
 * Class DispensaryRepository.
 *
 * @package namespace App\Models\Repositories\Admin\Dispensary;
 */
class DispensaryRepository extends BaseRepository implements DispensaryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Dispensary::class;
    }

    public function isAlphaExists($alphaId): bool
    {
        return $this->model->where('data->alpha_id', $alphaId)->exists();
    }

    public function getListingData(string $searchString, string $sortOn, string $sortOrder, string $status)
    {
        $query = $this->model->select(
            'dispensaries.id',
            'dispensaries.name',
            'dispensaries.service_fee_enabled',
            'dispensaries.service_fee_amount',
            'dispensary_customers.dispensary_id',
            DB::raw('count(dispensary_customers.id) as totalCustomer')
        );
        if (!empty($searchString)) {
            foreach (Dispensary::SEARCH_FIELDS as $field) {
                $query->orWhere('dispensaries.' . $field, 'LIKE', '%' . $searchString . '%');
            }
        }
        $query->leftJoin('dispensary_customers', 'dispensaries.id', '=', 'dispensary_customers.dispensary_id');
        $query->where('dispensaries.status', $status);
        return $query->with('domains')->with('dispensaryUser')
            ->orderBy($sortOn, $sortOrder)
            ->groupBy('dispensary_customers.dispensary_id')
            ->get();
    }

    public function getDispensaryNames(string $ids)
    {
        $query = $this->model->select(DB::raw('GROUP_CONCAT(name) as dispensary_names'))
            ->whereIn('id', explode(',', $ids))->pluck('dispensary_names')->toArray();
        return $query;
    }

    public function isRecordExists($alphaId): bool
    {
        return $this->model->where('data->alpha_id', $alphaId)->exists();
    }
}
