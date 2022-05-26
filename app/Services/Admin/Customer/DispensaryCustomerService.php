<?php

namespace App\Services\Admin\Customer;

use App\Models\Admin\Customer\DispensaryCustomer;
use App\Models\Repositories\Admin\Customer\DispensaryCustomerRepository;
use App\Models\Repositories\Admin\Dispensary\DispensaryRepository;

class DispensaryCustomerService
{
    protected $repository;
    protected $dispensaryCustomer;
    protected $dispensaryRepository;
    protected $alias;
    protected $customerAlias;
    protected $dispensaryId;

    public function __construct(
        DispensaryCustomer $dispensaryCustomer,
        DispensaryCustomerRepository $repository,
        DispensaryRepository $dispensaryRepository
    ) {
        $this->repository = $repository;
        $this->dispensaryCustomer = $dispensaryCustomer;
        $this->dispensaryRepository = $dispensaryRepository;
        $this->alias = 'dispensary_customers';
        $this->customerAlias = 'customers';
    }

    public function attachDocuments($request)
    {
        $requestData = $request->all();
        $document = $request->file('document_file');
        $dispensaryCustomer = $this->repository->find($requestData['customer_id']);
        $dispensaryCustomer->addMedia($document)->toMediaCollection($requestData['document_type']);
        return $dispensaryCustomer;
    }

    public function getListing($request)
    {
        $sortFields = [
            'id' => $this->alias . '.id',
            'name' => $this->alias . '.first_name',
            'email' => $this->customerAlias . '.email',
            'phone' => $this->customerAlias . '.phone'
        ];
        $sortFilter = $request->query('sortOn') ?? 'id';
        $sortOn = $sortFields[$sortFilter] ?? $this->alias . '.id';

        $sortOrder = $request->query('sort', DispensaryCustomer::DEFAULT_LIST_ORDER);
        $status = $request->query('customerStatus', '');
        $searchString = $request->query('search', '');

        return $this->repository->getListingData($searchString, $sortOn, $sortOrder, $status);
    }

    public function delete(int $customerId)
    {
        return $this->repository->delete($customerId);
    }
}
