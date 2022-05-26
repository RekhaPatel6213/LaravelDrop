<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customer\CustomerDocumentRequest;
use App\Http\Requests\Admin\Customer\CustomerRequest;
use App\Services\Admin\Customer\CustomerService;
use App\Services\Admin\Customer\DispensaryCustomerService;
use App\Transformers\Admin\Customer\CustomerDocumentTransformer;
use App\Transformers\Admin\Customer\CustomerImportDetailsTransformer;
use App\Transformers\Admin\Customer\CustomerImportHistoryTransformer;
use App\Transformers\Admin\Customer\CustomerImportTransformer;
use App\Transformers\Admin\Customer\CustomerTransformer;
use App\Transformers\Admin\Customer\CustomerListTransformer;
use App\Transformers\Admin\Customer\DispensaryCustomerListTransformer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerService;
    protected $listTransformer;
    protected $dcService;
    protected $transformer;
    protected $documentTransformer;
    protected $importTransformer;
    protected $historyTransformer;
    protected $detailsTransformer;
    protected $dcTransformer;

    public function __construct(
        CustomerService $customerService,
        DispensaryCustomerService $dcService,
        CustomerListTransformer $listTransformer,
        CustomerTransformer $transformer,
        CustomerDocumentTransformer $documentTransformer,
        CustomerImportTransformer $importTransformer,
        CustomerImportHistoryTransformer $historyTransformer,
        CustomerImportDetailsTransformer $detailsTransformer,
        DispensaryCustomerListTransformer $dcTransformer
    ) {
        $this->customerService = $customerService;
        $this->dcService = $dcService;
        $this->listTransformer = $listTransformer;
        $this->transformer = $transformer;
        $this->documentTransformer = $documentTransformer;
        $this->importTransformer = $importTransformer;
        $this->historyTransformer = $historyTransformer;
        $this->detailsTransformer = $detailsTransformer;
        $this->dcTransformer = $dcTransformer;
    }

    public function list(Request $request)
    {
        try {
            $data = $this->customerService->getListing($request);
            return $this->paginateCollection($data, $this->listTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }

    }

    public function dispensaryCustomerList(Request $request)
    {
        try {
            $data = $this->dcService->getListing($request);
            return $this->paginateCollection($data, $this->dcTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }



    public function store(CustomerRequest $request)
    {
        try {
            $customer = $this->customerService->save($request->all());

            return $this->item($customer, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function getCustomer(CustomerRequest $request)
    {
        try {
            $customerId = $request->route('customerId');
            $customer = $this->customerService->getCustomer($customerId);

            return $this->item($customer, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function update(CustomerRequest $request)
    {
        try {
            $customerId = $request->route('customerId');
            $customer = $this->customerService->update($request, $customerId);

            return $this->item($customer, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function changeStatus(CustomerRequest $request)
    {
        try {
            $customerId = $request->route('customerId');
            $customer = $this->customerService->updateCustomerOnly($request->all(), $customerId);

            return $this->item($customer, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $customerId = $request->route('customerId');
            $this->customerService->delete($customerId);

            return $this->returnJsonResponse(['message' => __('message.customer_deleted')]);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function deleteDispensaryCustomer(CustomerRequest $request)
    {
        try {
            $customerId = $request->route('customerId');
            $this->dcService->delete($customerId);

            return $this->returnJsonResponse(['message' => __('message.customer_deleted')]);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function attachDocuments(CustomerDocumentRequest $request)
    {
        try {
            $customer = $this->dcService->attachDocuments($request);
            return $this->item($customer, $this->documentTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function importPreviewCustomers(Request $request)
    {
        try {
            $import = $this->customerService->importCustomerDataToTable($request);
            $data = $this->customerService->decodeCustomerJsonData($import['data']);
            return $this->item($data, $this->importTransformer);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function importCustomers(CustomerRequest $request)
    {
        try {
            $previewId = $request->route('previewId');
            $this->customerService->importCustomers($previewId);

            return $this->returnJsonResponse(['message' => 'imported']);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function exportCustomers()
    {
        try {
            return $this->customerService->exportCustomers();
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function importHistory()
    {
        try {
            $customer = $this->customerService->customerImportHistory();
            return $this->collection($customer, $this->historyTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }


    public function importDetails(CustomerRequest $request)
    {
        try {
            $previewId = $request->route('previewId');
            $importData = $this->customerService->customerImportHistoryDetails($previewId);

            return $this->item($importData, $this->detailsTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }
}
