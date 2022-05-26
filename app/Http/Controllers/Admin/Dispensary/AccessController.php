<?php

namespace App\Http\Controllers\Admin\Dispensary;

use App\Http\Controllers\Controller;
use App\Settings\DispensaryAccessSettings;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('settingsService');
    }

    public function getAccess(int $dispensaryId, DispensaryAccessSettings $access)
    {
        try {
            $this->service->assignTenancy($dispensaryId);

            return $this->returnJsonResponse($access->toArray());
        } catch (Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function updateAccess(int $dispensaryId, Request $request, DispensaryAccessSettings $access)
    {
        try {
            $this->service->assignTenancy($dispensaryId);
            $access = $this->service->saveDispensaryAccess($access, $request->input());

            return $this->returnJsonResponse($access);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }
}
