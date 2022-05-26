<?php

namespace App\Models\Repositories\Admin;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Admin\Page;

/**
 * Class PageRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Admin;
 */
class PageRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Page::class;
    }

    /**
     * Page listing data
     *
     * @return mixed
     */
    public function getListingData(string $searchString, string $sortOn, string $sortOrder)
    {
        $query = $this->model->select('*');
        if (!empty($searchString)) {
            foreach (Page::SEARCH_FIELDS as $field) {
                $query->orWhere('pages.' . $field, 'LIKE', '%' . $searchString . '%');
            }
        }
        $query->where('dispensary_id', null);
        return $query->orderBy($sortOn, $sortOrder)->get();
    }

}
