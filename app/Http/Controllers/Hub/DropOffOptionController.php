<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use App\Services\Admin\Dispensary\DropOffOptionService;
use Illuminate\Http\Request;

class DropOffOptionController extends Controller
{
    protected $service;

    public function __construct(
        DropOffOptionService $service
    )
    {
        $this->service = $service;
    }

    public function getDropOffOptions()
    {
        try {
            $dropOffOptions = $this->service->getDropOffOptions();
            return $this->returnJsonResponse($dropOffOptions);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function saveDropOffOptions(Request $request)
    {
        try {
            $dropOptionId = $request->route('dropOptionId');
            $dropOffOptions = $this->service->store($request->all(), $dropOptionId);
            return $this->returnJsonResponse($dropOffOptions);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

}
