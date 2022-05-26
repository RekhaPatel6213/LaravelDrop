<?php

namespace App\Http\Controllers\Admin\Dispensary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Dispensary\SubscriptionPriceRequest;
use App\Transformers\Admin\Dispensary\invoiceTransformer;

class SubscriptionPriceController extends Controller
{
    protected $service;
    protected $invoiceTransformer;

    public function __construct(InvoiceTransformer $invoiceTransformer)
    {
        $this->service = app('subscriptionPriceService');
        $this->invoiceTransformer = $invoiceTransformer;
    }

    public function list()
    {
        try {
            $priceList = $this->service->list();
            return $this->returnJsonResponse($priceList);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function create(SubscriptionPriceRequest $request)
    {
        try {
            $price = $this->service->create($request->all());
            return $this->returnJsonResponse($price);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function stripeBalanceAdd(Request $request)
    {
        try {
            $balance = $this->service->stripeBalanceAdd($request->all());
            return $this->returnJsonResponse($balance);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function invoiceList(int $dispensaryId)
    {
        try {
            $invoiceList = $this->service->invoiceList($dispensaryId);
            return $this->collection($invoiceList, $this->invoiceTransformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function invoiceDetail(int $invoiceId)
    {
        try {
            $invoiceDetail = $this->service->invoiceDetail($invoiceId);
            return $this->item($invoiceDetail, $this->invoiceTransformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function stripeInvoiceList(int $dispensaryId)
    {
        try {
            $invoiceList = $this->service->stripeInvoiceList($dispensaryId);
            return $this->returnJsonResponse($invoiceList);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function stripeInvoiceDetail(Request $request)
    {
        try {
            $invoiceDetail = $this->service->stripeInvoiceDetail($request->all());
            return $this->returnJsonResponse($invoiceDetail);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function stripeCustomerBalanceTransaction(int $dispensaryId)
    {
        try {
            $transactionList = $this->service->stripeCustomerBalanceTransaction($dispensaryId);
            return $this->returnJsonResponse($transactionList);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
