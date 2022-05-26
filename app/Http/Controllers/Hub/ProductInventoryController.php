<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Hub\ProductAddStockRequest as AddStockRequest;
use App\Http\Requests\Hub\ProductInventoryRequest as InventoryRequest;
use App\Http\Requests\Hub\ReallocateInventoryRequest as ReallocateRequest;
use App\Transformers\Hub\Product\ProductInventoryTransformer as InventoryTransformer;
use App\Transformers\Hub\Product\ProductInventoryDetailsTransformer as DetailsTransformer;
use App\Models\Hub\Product;
use App\Exceptions\InventoryException;

class ProductInventoryController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('inventoryService');
        $this->inventoryTransformer = new InventoryTransformer;
        $this->detailsTransformer = new DetailsTransformer;
    }

    public function logList(Request $request)
    {
        try {
            $inventory = $this->service->inventoryLogList($request);
            return $this->collection($inventory, $this->inventoryTransformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function detail(int $productId, int $modelId = null)
    {
        try {
            $product = $this->service->productDetail($productId);
            return $this->item($product, $this->detailsTransformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function addStock(int $productId, AddStockRequest $request)
    {
        try {
            $product = $this->service->addStock($productId, $request->all());
            return $this->returnJsonResponse($product);
        } catch (InventoryException $e) {
            return $this->abortJsonResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function store(int $productId, InventoryRequest $request)
    {
        try {
            $product = $this->service->inventorystore($productId, $request->all());
            return $this->returnJsonResponse($product);
        } catch (InventoryException $e) {
            return $this->abortJsonResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function reallocate(int $inventoryId, ReallocateRequest $request)
    {
        try {
            $product = $this->service->inventoryReallocate($inventoryId, $request->all());
            return $this->returnJsonResponse($product);
        } catch (InventoryException $e) {
            return $this->abortJsonResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function delete(int $productId,int $modelId)
    {
        try {
            $product = $this->service->delete($productId, $modelId);
            return $this->returnJsonResponse($product);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
