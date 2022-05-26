<?php

namespace App\Models\Repositories\Admin;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Admin\Faq;

/**
 * Class FaqRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Admin;
 */
class FaqRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Faq::class;
    }

    /**
     * Faq listing data
     *
     * @return mixed
     */
    public function getListingData(string $searchString, string $sortOn, string $sortOrder)
    {
        $query = $this->model->select('*');
        if (!empty($searchString)) {
            foreach (Faq::SEARCH_FIELDS as $field) {
                $query->orWhere('faqs.' . $field, 'LIKE', '%' . $searchString . '%');
            }
        }
        $query->where('dispensary_id', null);
        return $query->orderBy($sortOn, $sortOrder)->get();
    }

}
