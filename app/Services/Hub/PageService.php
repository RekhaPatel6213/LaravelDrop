<?php

namespace App\Services\Hub;

use App\Models\Repositories\Hub\PageRepository;
use App\Http\Traits\DispensaryTrait;

class PageService
{
    use DispensaryTrait;
    private $pageRepository;
    private $alias;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
        $this->alias = 'pages';
    }

    /**
     * @param int $pageId
     * @return mixed
     */
    public function getHubLegal(int $pageId)
    {
        return $this->pageRepository->find($pageId);
    }

    /**
     * @param $requestData
     * @param $pageId
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($requestData, $pageId)
    {
        return $this->pageRepository->update($requestData, $pageId);
    }
}
