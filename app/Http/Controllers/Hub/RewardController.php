<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Hub\RewardRequest;
use App\Http\Requests\Hub\RewardCreateRequest;
use App\Services\Hub\RewardService;
use App\Transformers\Hub\RewardTransformer;


/**
 * Class RewardController.
 *
 * @package namespace App\Http\Controllers\Hub;
 */
class RewardController extends Controller
{
    /**
     * @var RewardService
     */
    protected $rewardService;

    /**
     * @var RewardTransformer
     */
    protected $rewardTransformer;

    /**
     * RewardController constructor.
     *
     * @param RewardService $rewardService
     * @param RewardTransformer $rewardTransformer
     */
    public function __construct(RewardService $rewardService, RewardTransformer $rewardTransformer)
    {
        $this->rewardService = $rewardService;
        $this->rewardTransformer  = $rewardTransformer;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */

    public function list(Request $request)
    {
        try {
            $data = $this->rewardService->getListing($request);
            return $this->paginateCollection($data, $this->rewardTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param RewardCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function save(RewardCreateRequest $request)
    {
        try {
            $requestData = $request->except('logo');
            $reward = $this->rewardService->save($requestData);
            return $this->item($reward, $this->rewardTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Display the specified resource.
     * @param int $rewardId
     * @return \Illuminate\Http\JsonResponse
     */

    public function getReward(int $rewardId)
    {
        try {
            $reward = $this->rewardService->getReward($rewardId);
            return $this->item($reward, $this->rewardTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $rewardId
     * @param RewardRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(int $rewardId, RewardRequest $request)
    {
        try {
            $requestData = $request->except('logo');
            $reward = $this->rewardService->update($requestData, $rewardId);
            return $this->item($reward, $this->rewardTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $rewardId
     * @return \Illuminate\Http\JsonResponse
     */

    public function delete(int $rewardId)
    {
        try {
            $reward = $this->rewardService->delete($rewardId);
            return $this->returnJsonResponse($reward);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
