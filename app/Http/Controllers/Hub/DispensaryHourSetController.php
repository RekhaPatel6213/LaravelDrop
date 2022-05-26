<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hub\HourSetRequest;
use App\Services\Admin\Dispensary\DispensaryHourSetService;

class DispensaryHourSetController extends Controller
{
    protected $service;

    public function __construct(
        DispensaryHourSetService $service
    )
    {
        $this->service = $service;
    }

    public function getShopTimings()
    {
        try {
            $timings = $this->service->getDispensaryTimings();
            if (!$timings['success']) {
                return response()->json(['message' => $timings['message']]);
            }
            return $this->returnJsonResponse($timings['data']);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage(), config('api.error_code'));
        }
    }

    public function updateShopTimings(HourSetRequest $request)
    {
        try {
            $timings = $this->service->updateShopTimings($request);
            if (!$timings['success']) {
                return response()->json(['message' => $timings['message']]);
            }
            return $this->returnJsonResponse($timings['data']);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage(), config('api.error_code'));
        }
    }

    public function deleteShopTimings(HourSetRequest $request)
    {
        try {
            $hourSetId = $request->route('hourSetId');
            $hourSet = $this->service->delete($hourSetId);
            if (!$hourSet['success']) {
                return response()->json(['message' => $hourSet['message']]);
            }
            return response()->json(['message' => __('message.hour_set_deleted')]);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage(), config('api.error_code'));
        }
    }

}
