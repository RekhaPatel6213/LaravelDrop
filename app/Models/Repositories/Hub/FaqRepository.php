<?php

namespace App\Models\Repositories\Hub;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Repositories\Contracts\Hub\FaqInterface;
use App\Models\Hub\Faq;

/**
 * Class FaqRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Hub;
 */
class FaqRepository extends BaseRepository implements FaqInterface
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
        return $query->orderBy($sortOn, $sortOrder)->get();
    }
}
