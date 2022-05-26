<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\DriverRequest;
use App\Http\Requests\Driver\DriverUpdateRequest;
use App\Services\Driver\DriverUserService;
use App\Transformers\Driver\DriverTransformer;
use Illuminate\Http\Request;

class DriverUserController extends Controller
{
    protected $driverService;
    protected $transformer;

    public function __construct( DriverUserService $driverService, DriverTransformer $transformer)
    {
        $this->driverService = $driverService;
        $this->transformer = $transformer;
    }

    public function list(Request $request)
    {
        try {
            $data = $this->driverService->getListing($request);
            return $this->paginateCollection($data, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }

    }

    public function store(DriverRequest $request)
    {
        try {
            $requestData = $request->all();
            if ($request->has('profile_image')) {
                $requestData['profileImageObj'] = $request->file('profile_image');
            }
            $driver = $this->driverService->save($requestData);
            return $this->item($driver, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function getDriver(int $driverId)
    {
        try {
            $driver = $this->driverService->getDriver($driverId);
            return $this->item($driver, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function update(int $driverId, DriverUpdateRequest $request)
    {
        try {
            $requestData = $request->all();
            if ($request->has('profile_image')) {
                $requestData['profileImageObj'] = $request->file('profile_image');
            }
            $driver = $this->driverService->update($requestData, $driverId);
            return $this->item($driver, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function delete(int $driverId, DriverUpdateRequest $request)
    {
        try {
            $driver = $this->driverService->delete($driverId);
            return $this->returnJsonResponse($driver);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
