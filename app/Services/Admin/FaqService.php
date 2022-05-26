<?php

namespace App\Services\Admin;

use App\Models\Admin\Faq;
use App\Models\Repositories\Admin\FaqRepository;

class FaqService
{
    private $faqRepository;
    private $alias;

    /**
     * FaqService constructor.
     * @param FaqRepository $faqRepository
     */
    public function __construct(FaqRepository $faqRepository)
    {
        $this->faqRepository = $faqRepository;
        $this->alias = 'faqs';
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getListing($request)
    {
        $sortOn = $request->query('sortOn', $this->alias . '.created_at');
        $sortOrder = $request->query('sort', Faq::DEFAULT_LIST_ORDER);
        $searchString = $request->query('search', '');

        return $this->faqRepository->getListingData($searchString, $sortOn, $sortOrder);
    }

    /**
     * @param $request
     * @return array
     */
    public function save($requestData)
    {
        return $this->faqRepository->create($requestData);
    }

    /**
     * @param int $faqId
     * @return mixed
     */
    public function getFaq(int $faqId)
    {
        return $this->faqRepository->find($faqId);
    }

    /**
     * @param $request
     * @param $faqId
     * @return array
     */
    public function update($requestData, $faqId)
    {
        return $this->faqRepository->update($requestData, $faqId);
    }

    /**
     * @param int $faqId
     * @return array
     */
    public function delete(int $faqId)
    {
        $this->faqRepository->delete($faqId);
        return ['data' => ['message' => "Faq Deleted Successfully"]];
    }
}