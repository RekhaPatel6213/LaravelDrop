<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Hub\SMSCampaignRequest;
use App\Services\Hub\SMSCampaignService;
use App\Transformers\Hub\SMSCampaignTransformer;

/**
 * Class SMSCampaignController.
 *
 * @package namespace App\Http\Controllers\Hub;
 */
class SMSCampaignController extends Controller
{
    /**
     * @var SMSCampaignService
     */
    protected $smsCampaignService;

    /**
     * @var SMSCampaignTransformer
     */
    protected $smsCampaignTransformer;

    /**
     * SMSCampaignController constructor.
     *
     * @param SMSCampaignService $smsCampaignService
     * @param SMSCampaignTransformer $smsCampaignTransformer
     */
    public function __construct(SMSCampaignService $smsCampaignService, SMSCampaignTransformer $smsCampaignTransformer)
    {
        $this->smsCampaignService = $smsCampaignService;
        $this->smsCampaignTransformer  = $smsCampaignTransformer;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */

    public function list(Request $request)
    {
        try {
            $data = $this->smsCampaignService->getListing($request);
            return $this->paginateCollection($data, $this->smsCampaignTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param SMSCampaignRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function save(SMSCampaignRequest $request)
    {
        try {
            $smsCampaign = $this->smsCampaignService->save($request->all());
            return $this->item($smsCampaign, $this->smsCampaignTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Display the specified resource.
     * @param int $smsCampaignId
     * @return \Illuminate\Http\JsonResponse
     */

    public function getSMSCampaign(int $smsCampaignId)
    {
        try {
            $smsCampaign = $this->smsCampaignService->getSMSCampaign($smsCampaignId);
            return $this->item($smsCampaign, $this->smsCampaignTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $smsCampaignId
     * @param SMSCampaignRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(int $smsCampaignId, SMSCampaignRequest $request)
    {
        try {
            $smsCampaign = $this->smsCampaignService->update($request->all(), $smsCampaignId);
            return $this->item($smsCampaign, $this->smsCampaignTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $smsCampaignId
     * @param SMSCampaignRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function totalCustomers()
    {
        try {
            $smsCampaign = $this->smsCampaignService->totalCustomers();
            return $this->returnJsonResponse($smsCampaign);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}