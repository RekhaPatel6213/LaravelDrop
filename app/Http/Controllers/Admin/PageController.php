<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\PageRequest;
use App\Services\Admin\PageService;
use App\Transformers\Admin\PageTransformer;

/**
 * Class PageController.
 *
 * @package namespace App\Http\Controllers\Admin;
 */
class PageController extends Controller
{
    /**
     * @var PageTransformer
     */
    protected $pageTransformer;

    /**
     * @var PageService
     */
    protected $pageService;

    /**
     * PageController constructor.
     *
     * @param PageService $pageService
     * @param PageTransformer $pageTransformer
     */
    public function __construct(PageService $pageService, PageTransformer $pageTransformer)
    {
        $this->pageService = $pageService;
        $this->pageTransformer = $pageTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        try {
            $data = $this->pageService->getListing($request);
            return $this->paginateCollection($data, $this->pageTransformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param PageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(PageRequest $request)
    {
        try {
            $page = $this->pageService->save($request->all());
            return $this->item($page, $this->pageTransformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Display the specified resource.
     * @param int $pageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPage(int $pageId)
    {
        try {
            $page = $this->pageService->getPage($pageId);
            return $this->item($page, $this->pageTransformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $pageId
     * @param PageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $pageId, PageRequest $request)
    {
        try {
            $page = $this->pageService->update($request->all(), $pageId);
            return $this->item($page, $this->pageTransformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $pageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $pageId)
    {
        try {
            $page = $this->pageService->delete($pageId);
            return $this->returnJsonResponse($page);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
