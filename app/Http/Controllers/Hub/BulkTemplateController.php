<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Hub\BulkTemplateRequest;
use App\Services\Hub\BulkTemplateService;
use App\Transformers\Hub\Product\BulkTemplateTransformer;

class BulkTemplateController extends Controller
{
    protected $service;
    
    public function __construct()
    {
        $this->service = app('bulkTemplateService');
        $this->transformer = new BulkTemplateTransformer;
    }

    public function list(Request $request)
    {
        try {
            $templates = $this->service->list($request->all());
            return $this->collection($templates, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function create(BulkTemplateRequest $request)
    {
        try {
            $template = $this->service->create($request->all());
            return $this->item($template, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function get(int $templateId)
    {
        try {
            $template = $this->service->get($templateId);
            return $this->item($template, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function update(int $templateId, BulkTemplateRequest $request)
    {
        try {
            $template = $this->service->update($request->all(), $templateId);
            return $this->item($template, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function delete(int $templateId, BulkTemplateRequest $request)
    {
        try {
            $template = $this->service->delete($templateId);
            return $this->returnJsonResponse($template);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
