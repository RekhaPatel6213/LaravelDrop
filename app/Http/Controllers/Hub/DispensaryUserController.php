<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Hub\DispensaryUserRequest;
use App\Services\Hub\DispensaryUserService;
use App\Transformers\Hub\DispensaryUserTransformer;

/**
 * Class DispensaryUserController.
 *
 * @package namespace App\Http\Controllers\Hub;
 */
class DispensaryUserController extends Controller
{
    /**
     * @var DispensaryUserService
     */
    protected $dispensaryUserService;

    /**
     * @var DispensaryUserTransformer
     */
    protected $dispensaryUserTransformer;

    /**
     * DispensaryUserController constructor.
     *
     * @param DispensaryUserService $dispensaryUserService
     * @param DispensaryUserTransformer $dispensaryUserTransformer
     */
    public function __construct(DispensaryUserService $dispensaryUserService, DispensaryUserTransformer $dispensaryUserTransformer)
    {
        $this->dispensaryUserService = $dispensaryUserService;
        $this->dispensaryUserTransformer  = $dispensaryUserTransformer;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */

    public function list(Request $request)
    {
        try {
            $data = $this->dispensaryUserService->getListing($request);
            return $this->paginateCollection($data, $this->dispensaryUserTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param DispensaryUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function save(DispensaryUserRequest $request)
    {
        try {
            $dispensaryUser = $this->dispensaryUserService->save($request->all());
            return $this->item($dispensaryUser, $this->dispensaryUserTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Display the specified resource.
     * @param int $dispensaryUserId
     * @return \Illuminate\Http\JsonResponse
     */

    public function getDispensaryUser(int $dispensaryUserId)
    {
        try {
            $dispensaryUser = $this->dispensaryUserService->getDispensaryUser($dispensaryUserId);
            return $this->item($dispensaryUser, $this->dispensaryUserTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getPermission(Request $request)
    {
        try {
            $permissions = $this->dispensaryUserService->getPermission($request->all());
            return $this->returnJsonResponse($permissions);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $dispensaryUserId
     * @param DispensaryUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(int $dispensaryUserId, DispensaryUserRequest $request)
    {
        try {
            $dispensaryUser = $this->dispensaryUserService->update($request->all(), $dispensaryUserId);
            return $this->item($dispensaryUser, $this->dispensaryUserTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $dispensaryUserId
     * @return \Illuminate\Http\JsonResponse
     */

    public function delete(int $dispensaryUserId, DispensaryUserRequest $request)
    {
        try {
            $dispensaryUser = $this->dispensaryUserService->delete($dispensaryUserId);
            return $this->returnJsonResponse($dispensaryUser);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
