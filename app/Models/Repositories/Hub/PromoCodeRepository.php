<?php

namespace App\Models\Repositories\Hub;

use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Hub\PromoCodeInterface;
use App\Models\Hub\PromoCode;

/**
 * Class PromoCodeRepository.
 *
 * @package namespace App\Models\Repositories\Hub;
 */
class PromoCodeRepository extends BaseRepository implements PromoCodeInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PromoCode::class;
    }

    public function getListingData(string $searchString, string $sortOn, string $sortOrder, string $status)
    {
        $query = $this->model->select('*');
        if (!empty($searchString)) {
            foreach (PromoCode::SEARCH_FIELDS as $field) {
                $query->orWhere('promo_codes.' . $field, 'LIKE', '%' . $searchString . '%');
            }
        }
        $query->where('promo_codes.status', $status);
        return $query->orderBy($sortOn, $sortOrder)->get();
    }

}
