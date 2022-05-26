<?php

namespace App\Models\Repositories\Hub;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Repositories\Contracts\Hub\PageInterface;
use App\Models\Hub\Page;

/**
 * Class PageRepository.
 *
 * @package namespace App\Models\Repositories\Hub;
 */
class PageRepository extends BaseRepository implements PageInterface
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
}
