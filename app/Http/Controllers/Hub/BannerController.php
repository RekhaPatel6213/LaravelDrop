<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Hub\BannerRequest;
use App\Services\Hub\BannerService;
use App\Transformers\Hub\BannerTransformer;

/**
 * Class BannerController.
 *
 * @package namespace App\Http\Controllers\Hub;
 */
class BannerController extends Controller
{
    /**
     * @var BannerService
     */
    protected $bannerService;

    /**
     * @var BannerTransformer
     */
    protected $bannerTransformer;

    /**
     * BannerController constructor.
     *
     * @param BannerService $bannerService
     * @param BannerTransformer $bannerTransformer
     */
    public function __construct(BannerService $bannerService, BannerTransformer $bannerTransformer)
    {
        $this->bannerService = $bannerService;
        $this->bannerTransformer  = $bannerTransformer;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */

    public function list(Request $request)
    {
        try {
            $data = $this->bannerService->getListing($request);
            return $this->paginateCollection($data, $this->bannerTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param BannerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function save(BannerRequest $request)
    {
        try {
            $requestData = $request->except('banner_image');
            $banner = $this->bannerService->save($requestData);
            return $this->item($banner, $this->bannerTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Display the specified resource.
     * @param int $bannerId
     * @return \Illuminate\Http\JsonResponse
     */

    public function getBanner(int $bannerId)
    {
        try {
            $banner = $this->bannerService->getBanner($bannerId);
            return $this->item($banner, $this->bannerTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getRedirectDetail(Request $request)
    {
        try {
            $redirect_detail = $this->bannerService->getRedirectDetail($request->all());
            return $this->returnJsonResponse($redirect_detail);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $bannerId
     * @param BannerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(int $bannerId, BannerRequest $request)
    {
        try {
            $requestData = $request->except('banner_image');
            $banner = $this->bannerService->update($requestData, $bannerId);
            return $this->item($banner, $this->bannerTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $bannerId
     * @return \Illuminate\Http\JsonResponse
     */

    public function delete(int $bannerId, BannerRequest $request)
    {
        try {
            $banner = $this->bannerService->delete($bannerId);
            return $this->returnJsonResponse($banner);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}

