<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transformers\Hub\CreditCardTransformer;
use App\Http\Requests\Hub\CreditCardRequest;
use App\Transformers\Admin\Dispensary\invoiceTransformer;

class CreditCardController extends Controller
{
    protected $service;
    protected $transformer;
    protected $invoiceTransformer;

    public function __construct(CreditCardTransformer $transformer, InvoiceTransformer $invoiceTransformer){
        $this->service = app('creditCardService');
        $this->subscriptioService = app('subscriptionPriceService');
        $this->transformer = $transformer;
        $this->invoiceTransformer = $invoiceTransformer;
    }

    public function subscription(){
        try {
            $subscription = $this->service->getSubscription();
            return $this->returnJsonResponse($subscription);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function invoiceList(Request $request)
    {
        try {
            $invoiceList = $this->service->invoiceList($request->all());
            return $this->collection($invoiceList, $this->invoiceTransformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function invoiceDetail(int $invoiceId)
    {
        try {
            $invoice = $this->subscriptioService->invoiceDetail($invoiceId);
            return $this->item($invoice, $this->invoiceTransformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function list(Request $request)
    {
        try {
            $creditCards = $this->service->list($request->all());
            return $this->collection($creditCards, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function store(CreditCardRequest $request)
    {
        try {
            $creditCard = $this->service->creditCardAdd($request->all());
            return $this->returnJsonResponse($creditCard);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function setDefault(int $creditCardId, CreditCardRequest $request)
    {
        try {
            $creditCard = $this->service->creditCardDefault($creditCardId);
            return $this->returnJsonResponse($creditCard);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function delete(int $creditCardId, CreditCardRequest $request)
    {
        try {
            $creditCard = $this->service->creditCardDelete($creditCardId);
            return $this->returnJsonResponse($creditCard);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
