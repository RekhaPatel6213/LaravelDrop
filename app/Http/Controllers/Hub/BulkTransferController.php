<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Hub\BulkTransferRequest;
use App\Http\Requests\Hub\BulkTransferProductRequest;
use App\Transformers\Hub\Product\BulkTransferTransformer;

class BulkTransferController extends Controller
{
    protected $service, $transformer;
    
    public function __construct()
    {
        $this->service = app('bulkTransferService');
        $this->transformer = new BulkTransferTransformer;
    }

    public function getProducts(BulkTransferProductRequest $request)
    {
        try {
            $products = $this->service->getProducts($request->all());
            return $this->returnJsonResponse($products);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function transfer(BulkTransferRequest $request)
    {
        try {
            $bulkTransfer = $this->service->bulkTransfer($request->all());
            return $this->returnJsonResponse($bulkTransfer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function get(int $transferId)
    {
        try {
            $bulkTransfer =  $this->service->get($transferId);
            return $this->item($bulkTransfer, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
