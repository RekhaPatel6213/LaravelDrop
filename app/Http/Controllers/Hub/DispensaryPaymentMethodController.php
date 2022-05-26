<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Dispensary\DispensaryPaymentMethodRequest;
use App\Services\Admin\Dispensary\DispensaryPaymentMethodService;
use App\Transformers\Admin\Dispensary\DispensaryPaymentMethodTransformer;
use Illuminate\Http\Request;

class DispensaryPaymentMethodController extends Controller
{
    protected $service;
    protected $transformer;

    public function __construct(
        DispensaryPaymentMethodService $service,
        DispensaryPaymentMethodTransformer $transformer
    )
    {
        $this->service = $service;
        $this->transformer = $transformer;
    }

    public function addPaymentMethod(Request $request)
    {
        try {
            $paymentMethods = $this->service->store($request->all());
            return $this->item($paymentMethods, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function updatePaymentMethods(DispensaryPaymentMethodRequest $request)
    {
        try {
            $paymentMethods = $this->service->updatePaymentMethods($request->all());
            return $this->collection($paymentMethods, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function getPaymentMethods()
    {
        try {
            $paymentMethods = $this->service->getAllPaymentMethods();

            return $this->collection($paymentMethods, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }
}
