<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use App\Http\Requests\Territory\TaxesCostsUpdateRequest;
use App\Services\Territory\TaxesCostsSettingService;
use Illuminate\Http\Request;

class TaxesCostsSettingController extends Controller
{
    protected $service;

    public function __construct(
        TaxesCostsSettingService $service
    )
    {
        $this->service = $service;
    }

    public function getDeliveryCosts(Request $request)
    {
        try {
            $taxesData = $this->service->getDeliveryListing($request);
            return $this->returnJsonResponse($taxesData);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function getTaxes(Request $request)
    {
        try {
            $taxesData = $this->service->getTaxListing($request);
            return $this->returnJsonResponse($taxesData);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function updateTaxesDeliveryCosts(TaxesCostsUpdateRequest $request)
    {
        try {
            $taxesData = $this->service->updateTaxesCosts($request->all());
            return $this->returnJsonResponse($taxesData);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

}
