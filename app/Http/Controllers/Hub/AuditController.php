<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Hub\AuditProductRequest;
use App\Http\Requests\Hub\AuditCreateRequest;
use App\Transformers\Hub\AuditTransformer;
use App\Exceptions\InventoryException;

class AuditController extends Controller
{
    protected $service, $transformer;

    public function __construct(AuditTransformer $auditTransformer)
    {
        $this->service = app('auditService');
        $this->transformer = $auditTransformer;
    }

    public function getProducts(AuditProductRequest $request)
    {
        try {
            $products = $this->service->getProducts($request->all());
            return $this->returnJsonResponse($products);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function list(Request $request)
    {
        try {
            $audits = $this->service->list($request->all());
            return $this->paginateCollection($audits, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function store(AuditCreateRequest $request)
    {
        try {
            return $audit = $this->service->store($request->all());
            return $this->item($audit, $this->transformer);
        } catch (InventoryException $e) {
            return $this->abortJsonResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function get(int $auditId)
    {
        try {
            $audit = $this->service->get($auditId);
            return $this->item($audit, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

     public function export(int $auditId)
    {
        try {
            return $this->service->export($auditId);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
