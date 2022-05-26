<?php

namespace App\Services\Admin;

use App\Models\Admin\Page;
use App\Models\Repositories\Admin\PageRepository;

class PageService
{
    private $pageRepository;
    private $alias;

    /**
     * PageService constructor.
     * @param PageRepository $pageRepository
     */
    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
        $this->alias = 'pages';
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getListing($request)
    {
        $sortOn = $request->query('sortOn', $this->alias . '.created_at');
        $sortOrder = $request->query('sort', Page::DEFAULT_LIST_ORDER);
        $searchString = $request->query('search', '');
      
        return $this->pageRepository->getListingData($searchString, $sortOn, $sortOrder);
    }

    /**
     * @param $request
     * @return array
     */
    public function save($requestData)
    {
        return $this->pageRepository->create($requestData);
    }

    /**
     * @param int $pageId
     * @return mixed
     */
    public function getPage(int $pageId)
    {
        return $this->pageRepository->find($pageId);
    }

    /**
     * @param $request
     * @param $pageId
     * @return array
     */
    public function update($requestData, $pageId)
    {
        return $this->pageRepository->update($requestData, $pageId);
    }

    /**
     * @param int $pageId
     * @return array
     */
    public function delete(int $pageId)
    {
        $this->pageRepository->delete($pageId);
        return ['data' => ['message' => "Page Deleted Successfully"]];
    }

}
