<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmailTemplateRequest;
use App\Services\Admin\EmailTemplateService;
use App\Transformers\Admin\EmailTemplateListTransformer;
use App\Transformers\Admin\EmailTemplateTransformer;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    protected $service;
    protected $listTransformer;
    protected $transformer;

    public function __construct(
        EmailTemplateService $service,
        EmailTemplateListTransformer $listTransformer,
        EmailTemplateTransformer $transformer
    ) {
        $this->service = $service;
        $this->listTransformer = $listTransformer;
        $this->transformer = $transformer;
    }

    public function list(Request $request)
    {
        try {
            $data = $this->service->getListing($request);
            return $this->paginateCollection($data, $this->listTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }


    public function update(int $templateId, EmailTemplateRequest $request)
    {
        try {
            $template = $this->service->update($request->all(), $templateId);
            
            return $this->item($template, $this->listTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function getSingleEmailTemplate(int $templateId)
    {
        try {
            $template = $this->service->find($templateId);

            return $this->item($template, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }
}
