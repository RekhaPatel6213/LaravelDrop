<?php

namespace App\Services\Hub;

use App\Models\Hub\SMSCampaign;
use App\Models\Repositories\Hub\SMSCampaignRepository;
use App\Models\Repositories\Territory\TerritoryRepository;
use App\Models\Repositories\Admin\Customer\CustomerRepository;
use App\Models\Admin\Customer\DispensaryCustomer;
use App\Http\Traits\DispensaryTrait;
use Illuminate\Support\Facades\Auth;

class SMSCampaignService
{
    use DispensaryTrait;
    private $smsCampRepository;
    private $territoryRepository;
    private $customerRepository;
    private $alias;
    private $dispensaryId;

    public function __construct(SMSCampaignRepository $smsCampRepository, TerritoryRepository $territoryRepository, CustomerRepository $customerRepository) {
        $this->smsCampRepository = $smsCampRepository;
        $this->territoryRepository = $territoryRepository;
        $this->customerRepository = $customerRepository;
        $this->alias = 'sms_campaigns';
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getListing($request)
    {
        $sortOn = $request->query('sortOn', $this->alias . '.created_at');
        $sortOrder = $request->query('sort', SMSCampaign::DEFAULT_LIST_ORDER);
        $searchString = $request->query('search', '');

        $smsCampaign = $this->smsCampRepository->getListingData($searchString, $sortOn, $sortOrder);
        foreach ($smsCampaign as $key => $sms) {
            $territoryNames = $this->territoryRepository->findWhereIn('id', $sms->territory_ids)->pluck('name')->toArray();
            $territoryNames = implode(',', $territoryNames);
            $smsCampaign[$key]['territory_names'] = $territoryNames;
        }
        return $smsCampaign;
    }

    /**
     * @param $requestData
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function save(array $requestData)
    {
        $requestData['added_by'] = Auth()->user()->id;
        $requestData['schedule_date'] = ($requestData['type_scheduled'] === SMSCampaign::TYPE_SCHEDULE['SEND_LATER']) ? $requestData['schedule_date'] : NULL ;
        $requestData['schedule_time'] = ($requestData['type_scheduled'] === SMSCampaign::TYPE_SCHEDULE['SEND_LATER']) ? $requestData['schedule_time'] : [NULL];
        return $this->smsCampRepository->create($requestData);
    }

    /**
     * @param int $smsCampId
     * @return mixed
     */
    public function getSMSCampaign(int $smsCampId)
    {
        return $this->smsCampRepository->find($smsCampId);
    }

    /**
     * @param $requestData
     * @param $smsCampId
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($requestData, $smsCampId)
    {
        return $this->smsCampRepository->update($requestData, $smsCampId);
    }

    public function totalCustomers()
    {
        $totalCustomer[] = $this->customerRepository->has('dispensaryCustomer')->count();
        return $totalCustomer;
    }
}
