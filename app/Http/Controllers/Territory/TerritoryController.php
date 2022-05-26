<?php

namespace App\Http\Controllers\Territory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Territory\TerritoryRequest;
use App\Http\Requests\Territory\TerritoryIdRequest;
use App\Services\Territory\TerritoryService;
use App\Transformers\Territory\TerritoryListTransformer;
use App\Transformers\Territory\TerritoryTransformer;
use Illuminate\Http\Request;

class TerritoryController extends Controller
{
    protected $service;
    protected $listTransformer;
    protected $transformer;

    public function __construct(
        TerritoryService $service,
        TerritoryListTransformer $listTransformer,
        TerritoryTransformer $transformer,
    ) {
        $this->service = $service;
        $this->listTransformer = $listTransformer;
        $this->transformer = $transformer;
    }

    public function list(Request $request)
    {
        try {
            $data = $this->service->list($request->all());
            return $this->paginateCollection($data, $this->listTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function ajaxTerritories()
    {
        try {
            $data = $this->service->getAjaxTerritories();
            return $this->returnJsonResponse($data);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function get(int $id, TerritoryIdRequest $request)
    {
        try {
            $territory = $this->service->get($id);
            return $this->item($territory, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function store(TerritoryRequest $request)
    {
        try {
            $territory = $this->service->save($request->all());
            return $this->item($territory, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function update(int $id, TerritoryRequest $request)
    {
        try {
            return $territory = $this->service->save($request->all(), $id);
            return $this->item($territory, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function delete($id, TerritoryIdRequest $request)
    {
        try {
            $territory = $this->service->delete($id);
            return $this->returnJsonResponse($territory);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }
}
