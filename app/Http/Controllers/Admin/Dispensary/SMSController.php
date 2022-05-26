<?php

namespace App\Http\Controllers\Admin\Dispensary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Dispensary\SMSPurchase;
use App\Http\Requests\Admin\Dispensary\SMSRequest;
use App\Transformers\Admin\Dispensary\SubscriptionPriceTransformer;

class SMSController extends Controller
{
    protected $transformer;
    protected $service;

    public function __construct()
    {
        $this->service = app('SMS.Service');
        $this->transformer = new SubscriptionPriceTransformer;
    }

    public function history(int $dispensaryId)
    {
        $smsData = $this->service->getQueryBuilder($dispensaryId);
        return $this->returnJsonResponse($smsData);
    }

    public function getSMSGroups()
    {
        $smsGroups = $this->service->getSMSGroups();
        return $this->returnJsonResponse($smsGroups);
    }

    public function getSMSPrices(Request $request)
    {
        $requestData = $request->query();
        $smsPlans = $this->service->getSMSPrices($requestData);
        return $this->collection($smsPlans, $this->transformer);
    }

    public function purchaseSMS(SMSRequest $request)
    {
        try {
            $smsPurchase = $this->service->purchaseSMS($request->all());
            return $this->returnJsonResponse($smsPurchase);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function deductSMS(SMSRequest $request)
    {
        try {
            $deductSms = $this->service->deductSMS($request->all());
            return $this->returnJsonResponse($deductSms);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
