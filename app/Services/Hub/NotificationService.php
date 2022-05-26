<?php

namespace App\Services\Hub;

use App\Models\Hub\Notification;
use App\Models\Repositories\Hub\NotificationRepository;
use App\Models\Repositories\Admin\Customer\CustomerRepository;
use App\Http\Traits\DispensaryTrait;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    use DispensaryTrait;
    private $notificatRepository;
    protected $customerRepository;
    private $alias;

    public function __construct(NotificationRepository $notificatRepository, CustomerRepository $customerRepository) {
        $this->notificatRepository = $notificatRepository;
        $this->customerRepository = $customerRepository;
        $this->alias = 'notifications';
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getListing($request)
    {
        $sortOn = $request->query('sortOn', $this->alias . '.created_at');
        $sortOrder = $request->query('sort', Notification::DEFAULT_LIST_ORDER);
        $searchString = $request->query('search', '');

        return $this->notificatRepository->getListingData($searchString, $sortOn, $sortOrder);
    }

    /**
     * @param $requestData
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function save($requestData)
    {
        $requestData['added_by'] = Auth()->user()->id;
        $notification = $this->notificatRepository->create($requestData);
        $customers = $this->customerRepository->has('dispensaryCustomer')->get();
        $this->notificatRepository->createCustomerNotification($notification, $customers);

        return $notification;
    }

    /**
     * @param int $notificationId
     * @return array
     */
    public function delete(int $notificationId)
    {
        $this->notificatRepository->delete($notificationId);
        return ['data' => ['message' => "Notification Deleted Successfully"]];
    }
}
