<?php

namespace App\Models\Repositories\Hub;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Repositories\Contracts\Hub\SMSCampaignInterface;
use App\Models\Hub\SMSCampaign;

/**
 * Class SMSCampaignRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Hub;
 */
class SMSCampaignRepository extends BaseRepository implements SMSCampaignInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SMSCampaign::class;
    }

    /**
     * Notification listing data
     *
     * @return mixed
     */
    public function getListingData(string $searchString, string $sortOn, string $sortOrder)
    {
        $query = $this->model->select('*');
        if (!empty($searchString)) {
            foreach (SMSCampaign::SEARCH_FIELDS as $field) {
                $query->orWhere('sms_campaigns.' . $field, 'LIKE', '%' . $searchString . '%');
            }
        }
        return $query->orderBy($sortOn, $sortOrder)->get();
    }
}
