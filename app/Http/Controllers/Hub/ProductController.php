<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use App\Http\Requests\CsvRequest;
use App\Http\Requests\Hub\ProductIdRequest;
use App\Http\Requests\Hub\ProductRequest;
use App\Http\Requests\ImportPreviewRequest;
use App\Http\Traits\ProductTrait;
use App\Transformers\GenericImportTransformer;
use App\Transformers\Hub\Product\ProductTransformer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Validators\ValidationException;

class ProductController extends Controller
{
    protected $service;
    use ProductTrait;

    public function __construct()
    {
        $this->service = app('productService');
        $this->transformer = new ProductTransformer();
        $this->importTransformer = new GenericImportTransformer();
    }

    public function list(Request $request)
    {
        try {
            $products = $this->service->list($request);

            return $this->returnJsonResponse($products);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function allList(Request $request)
    {
        try {
            $products = $this->service->allList($request);

            return $this->paginateCollection($products, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function get(int $productId, ProductIdRequest $request)
    {
        try {
            $product = $this->service->get($productId);

            return $this->item($product, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function create(ProductRequest $request)
    {
        try {
            $product = $this->service->create($request->except('logo'));

            return $this->item($product, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function update(int $productId, ProductRequest $request)
    {
        try {
            $product = $this->service->update($request->except('logo'), $productId);

            return $this->item($product, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function updateAll(Request $request)
    {
        try {
            $product = $this->service->updateAll($request->all());

            return $this->returnJsonResponse($product);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function delete(int $productId, ProductIdRequest $request)
    {
        try {
            $product = $this->service->delete($productId);

            return $this->returnJsonResponse($product);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function deleteAll(Request $request)
    {
        try {
            $product = $this->service->deleteAll($request->all());

            return $this->returnJsonResponse($product);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function variant(Request $request)
    {
        try {
            $variants = $this->getVariantList($request->all());

            return $this->returnJsonResponse($variants);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }


    public function ajaxList()
    {
        $variants = $this->service->ajaxList();
        return $this->returnJsonResponse($variants);
    }
    
    public function export($type)
    {
        try {
            return $this->service->getExportData($type);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function importHistory()
    {
        try {
            $histories = $this->service->importHistory();

            return $this->collection($histories, $this->importTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function import(CsvRequest $request)
    {
        try {
            $importData = $this->service->importData($request);

            return $this->item($importData, $this->importTransformer);
        } catch (ValidationException $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function importPreview(int $previewId, ImportPreviewRequest $request)
    {
        try {
            $importData = $this->service->importPreview($previewId, $request->all());

            return $this->returnJsonResponse($importData);
        } catch (ValidationException $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function importDetail(int $previewId, ImportPreviewRequest $request)
    {
        try {
            $importData = $this->service->importDetail($previewId);

            return $this->item($importData, $this->importTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }
}
