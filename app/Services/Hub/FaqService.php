<?php

namespace App\Services\Hub;

use App\Models\Repositories\Hub\FaqRepository;
use App\Models\Hub\Faq;
use App\Http\Traits\DispensaryTrait;

class FaqService
{
    use DispensaryTrait;
    private $faqRepository;
    private $alias;

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
     * @param $requestData
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function save($requestData)
    {
        return $this->faqRepository->create($requestData);
    }

    /**
     * @param int $faqId
     * @return mixed
     */
    public function getHubFaq(int $faqId)
    {
        return $this->faqRepository->find($faqId);
    }

    /**
     * @param $requestData
     * @param $faqId
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
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