<?php

namespace App\Models\Repositories\Hub;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Repositories\Contracts\Hub\NotificationInterface;
use App\Models\Hub\Notification;

/**
 * Class NotificationRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Hub;
 */
class NotificationRepository extends BaseRepository implements NotificationInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Notification::class;
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
            foreach (Notification::SEARCH_FIELDS as $field) {
                $query->orWhere('notifications.' . $field, 'LIKE', '%' . $searchString . '%');
            }
        }
        return $query->orderBy($sortOn, $sortOrder)->get();
    }

    /**
     * @param Notification $notification
     * @param $customers
     */
    public function createCustomerNotification(Notification $notification, $customers){
        foreach ($customers as $customer){
            $notification->customerNotification()->create([
                'notification_id' => $notification->id,
                'customer_id' => $customer->id
            ]);
        }
    }
}
