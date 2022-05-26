<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\FaqRequest;
use App\Services\Admin\FaqService;
use App\Transformers\Admin\FaqTransformer;

class FaqController extends Controller
{
    /**
     * @var FaqTransformer
     */
    protected $faqTransformer;

    /**
     * @var FaqService
     */
    protected $faqService;

    /**
     * FaqController constructor.
     *
     * @param FaqService $faqService
     * @param FaqTransformer $faqTransformer
     */
    public function __construct(FaqService $faqService, FaqTransformer $faqTransformer)
    {
        $this->faqService = $faqService;
        $this->faqTransformer = $faqTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        try {
            $data = $this->faqService->getListing($request);
            return $this->paginateCollection($data, $this->faqTransformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param FaqRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(FaqRequest $request)
    {
        try {
            $faq = $this->faqService->save($request->all());
            return $this->item($faq, $this->faqTransformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Display the specified resource.
     * @param int $faqId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFaq(int $faqId)
    {
        try {
            $faq = $this->faqService->getFaq($faqId);
            return $this->item($faq, $this->faqTransformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $faqId
     * @param FaqRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $faqId, FaqRequest $request)
    {
        try {
            $faq = $this->faqService->update($request->all(), $faqId);
            return $this->item($faq, $this->faqTransformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $faqId
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $faqId)
    {
        try {
            $faq = $this->faqService->delete($faqId);
            return $this->returnJsonResponse($faq);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
