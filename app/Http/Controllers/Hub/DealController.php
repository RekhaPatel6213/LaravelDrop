<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hub\DealRequest;
use App\Services\Hub\BrandService;
use App\Services\Hub\DealService;
use App\Transformers\Hub\BrandTransformer;
use App\Transformers\Hub\DealTransformer;
use Illuminate\Http\Request;

class DealController extends Controller
{
    protected $service;
    protected $brandService;
    protected $transformer;
    protected $brandTransformer;

    public function __construct(
        DealService $service,
        BrandService $brandService,
        DealTransformer $transformer,
        BrandTransformer $brandTransformer
    )
    {
        $this->service = $service;
        $this->brandService = $brandService;
        $this->transformer = $transformer;
        $this->brandTransformer = $brandTransformer;
    }

    public function list(Request $request)
    {
        try {
            $data = $this->service->list($request);
            return $this->paginateCollection($data, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function getDeal(DealRequest $request)
    {
        try {
            $dealId = $request->route('dealId');
            $deal = $this->service->find($dealId);
            return $this->item($deal, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function store(DealRequest $request)
    {
        try {
            $dealId = $request->route('dealId');
            $requestData = $request->all();
            $deal = $this->service->store($requestData, $dealId);
            return $this->item($deal, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function update(DealRequest $request)
    {
        try {
            $dealId = $request->route('dealId');
            $requestData = $request->all();
            $deal = $this->service->update($requestData, $dealId);
            return $this->item($deal, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function delete(DealRequest $request)
    {
        try {
            $dealId = $request->route('dealId');
            $this->service->delete($dealId);
            return $this->returnJsonResponse(['message' => __('message.deal_deleted')], 200);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function brandList(Request $request)
    {
        try {
            $search = $request->route('search');
            $data = $this->brandService->brandList($search);
            return $this->collection($data, $this->brandTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }
}
