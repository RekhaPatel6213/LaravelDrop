<?php

namespace App\Services\Admin\Customer;

use App\Events\Admin\Customer\CustomerCreated;
use App\Events\Admin\Customer\CustomerUpdated;
use App\Exports\CustomerExport;
use App\Imports\CustomerImport;
use App\Models\Admin\Customer\Customer;
use App\Models\Repositories\Admin\Customer\CustomerRepository;
use App\Models\Repositories\Admin\Customer\DispensaryCustomerRepository;
use App\Models\Repositories\Admin\Dispensary\DispensaryRepository;
use App\Models\Repositories\GenericImportRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class CustomerService
{
    protected $dispensaryRepository;
    protected $customerRepository;
    protected $dcRepository;
    protected $importRepository;
    protected $alias;
    protected $dcAlias;

    public function __construct(
        DispensaryRepository $dispensaryRepository,
        CustomerRepository $customerRepository,
        DispensaryCustomerRepository $dcRepository,
        GenericImportRepository $importRepository
    ) {
        $this->dispensaryRepository = $dispensaryRepository;
        $this->customerRepository = $customerRepository;
        $this->dcRepository = $dcRepository;
        $this->importRepository = $importRepository;
        $this->alias = 'customers';
        $this->dcAlias = 'dispensary_customers';
    }

    public function save($args)
    {
        $phone = $args['phone'] ?? 0;
        $customer = $this->customerRepository->findByField('phone', $phone);

        if ($customer->isEmpty()) {
            $args['password'] = Hash::make(Str::random(32));
            $customerObj = $this->customerRepository->create($args);

            event(new CustomerCreated($customerObj, $args));
        } else {
            $customer = $customer->first();
            $customerObj = $this->customerRepository->update($args, $customer->id);

            event(new CustomerUpdated($customerObj, $args));
        }

        return $this->customerRepository->withFind('dispensaryCustomer', $customerObj->id);
    }

    public function update($args, $customerId)
    {
        $dispCustomer = $this->dcRepository->find($customerId);
        $customerObj = $this->customerRepository->update($args, $dispCustomer->customer_id);
        event(new CustomerUpdated($customerObj, $args));

        return $this->customerRepository->withFind('dispensaryCustomer', $customerObj->id);
    }

    public function updateCustomerOnly($args, $customerId)
    {
        $customerObj = $this->customerRepository->update($args, $customerId);
        return $this->customerRepository->withFind('dispensaryCustomer', $customerObj->id);
    }



    public function getListing($request)
    {
        $sortFields = [
            'id' => $this->alias . '.id',
            'name' => 'dc.full_names',
            'email' => $this->alias . '.email',
            'phone' => $this->alias . '.phone'
        ];
        $sortFilter = $request->query('sortOn') ?? 'id';
        $sortOn = $sortFields[$sortFilter] ?? $this->alias . '.id';

        $sortOrder = $request->query('sort', Customer::DEFAULT_LIST_ORDER);
        $status = $request->query('customerStatus', Customer::DEFAULT_LIST_STATUS);
        $searchString = $request->query('search', '');

        $allData = $this->customerRepository->getListingData($searchString, $sortOn, $sortOrder, $status);

        if (!empty($allData)) {
            foreach ($allData as $key => $data) {
                $allData[$key]['dispensary_names'] =
                    !empty($data['dispensaries']) ? $this->dispensaryRepository->getDispensaryNames($data['dispensaries']) : '';
                $allData[$key]['customer_names'] = explode(',', $data['full_names']);
            }
        }

        return $allData;
    }

    public function getCustomer(int $customerId)
    {
        return $this->customerRepository->withFind('dispensaryCustomer', $customerId);
    }

    public function delete(int $customerId)
    {
        return $this->customerRepository->delete($customerId);
    }

    public function importCustomerDataToTable($request)
    {
        if ($request->hasFile('customer_data')) {
            $file = $request->file('customer_data');
            $path = $file->store('public/imports');
            $importClass = App::make(CustomerImport::class);
            Excel::import($importClass, $path);

            $data = $this->importRepository->getLastImportData(CustomerImport::IMPORT_KEY);
            return $data;
        }
        return [];
    }

    public function exportCustomers()
    {
        $exportClass = App::make(CustomerExport::class);
        $fileName = 'New_Drop_List_' . time() . '.csv';
        return Excel::download($exportClass, $fileName);
    }

    public function importCustomers($previewId)
    {
        $data = $this->importRepository->getPendingPreviewData($previewId)->toArray();

        if (empty($data)) {
            return ['success' => false, 'message' => 'record_not_found'];
        }

        $decode = $this->decodeCustomerJsonData($data);
        $importData = array_merge($decode['existing'], $decode['new']);
        foreach ($importData as $importDatum) {
            if ($importDatum['is_new']) {
                $importDatum['password'] = Hash::make(Str::random(32));
                $customerObj = $this->customerRepository->create($importDatum);

                event(new CustomerCreated($customerObj, $importDatum));
            } else {
                $customer = $this->customerRepository->findByField('phone', $importDatum['phone']);
                $customer = $customer->first();
                $customerObj = $this->customerRepository->update($importDatum, $customer->id);

                event(new CustomerUpdated($customerObj, $importDatum));
            }
        }
        return $this->importRepository->update(['status' => 'COMPLETED'], $previewId);
    }

    public function customerImportHistory()
    {
        $historyData = [];
        $importData = $this->importRepository->findWhere(
            [
                'import_type' => 'customer',
                'status' => 'COMPLETED'
            ]
        );
        if ($importData->isEmpty()) {
            return $historyData;
        }

        foreach ($importData as $data) {
            [$new, $existing] = $this->getNewExistingCounts($data);
            $historyData[] = [
                'id' => $data->id,
                'import_date' => $data->created_at,
                'new' => $new,
                'existing' => $existing,
            ];
        }
        return $historyData;
    }

    public function customerImportHistoryDetails(int $previewId)
    {
        $importData = $this->importRepository->find($previewId);
        $decoded = $this->decodeCustomerJsonData($importData);
        $decoded['import_date'] = $importData->created_at;
        return $decoded;
    }


    public function decodeCustomerJsonData($customer)
    {
        $new = $existing = [];
        $data = json_decode($customer['data'], true);

        foreach ($data as $key => $value) {
            $value['is_new'] ? $new[$key] = $value : $existing[$key] = $value;
        }

        return [
            'preview_id' => $customer['id'],
            'existing' => $existing,
            'new' => $new,
        ];
    }

    public function getNewExistingCounts($data)
    {
        $preview = $this->decodeCustomerJsonData($data);
        return [count($preview['new']), count($preview['existing'])];
    }
}
