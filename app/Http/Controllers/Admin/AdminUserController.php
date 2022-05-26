<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AdminUserRequest;
use App\Http\Requests\Admin\AdminUserIdRequest;
use App\Transformers\Admin\AdminUserTransformer;
use App\Models\Admin\AdminUser;

class AdminUserController extends Controller
{
    protected $transformer;
    protected $service;

    public function __construct()
    {
        $this->service = app('adminService');
        $this->transformer = new AdminUserTransformer;
    }

    public function adminList(Request $request)
    {
        try {
            $usersData = $this->service->getQueryBuilder($request);
            return $this->paginateCollection($usersData, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(AdminUserRequest $request)
    {
        try {
            $adminUser = $this->service->storeAdmin($request->all());
            return $this->item($adminUser, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function getAdmin($adminId, AdminUserIdRequest $request)
    {
        try {
            $adminUser = $this->service->getAdmin($adminId);
            return $this->item($adminUser, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function update($adminId, AdminUserRequest $request)
    {
        try {
            $adminUser = $this->service->getAdmin($adminId);
            $adminUser = $this->service->storeAdmin($request->all(), $adminUser);
            return $this->item($adminUser, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function delete($adminId, AdminUserIdRequest $request)
    {
        try {
            $adminUser = $this->service->deleteAdmin($adminId);
            return $this->returnJsonResponse($adminUser);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
