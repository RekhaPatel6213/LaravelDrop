<?php

namespace App\Http\Controllers\Hub;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hub\VendorRequest;
use App\Http\Requests\Hub\VendorCreateRequest;
use App\Services\Hub\VendorService;
use App\Transformers\Hub\VendorTransformer;


/**
 * Class VendorController.
 *
 * @package namespace App\Http\Controllers\Hub;
 */
class VendorController extends Controller
{
    /**
     * @var VendorService
     */
    protected $vendorService;

    /**
     * @var VendorTransformer
     */
    protected $vendorTransformer;

    /**
     * VendorController constructor.
     *
     * @param VendorService $vendorService
     * @param VendorTransformer $vendorTransformer
     */
    public function __construct(VendorService $vendorService, VendorTransformer $vendorTransformer)
    {
        $this->vendorService = $vendorService;
        $this->vendorTransformer  = $vendorTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $data = $this->vendorService->getListing($request);
        return $this->paginateCollection($data, $this->vendorTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  VendorCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function save(VendorCreateRequest $request)
    {
        try {
            $vendor = $this->vendorService->save($request->all());
            return $this->item($vendor, $this->vendorTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  VendorRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function getVendor(int $vendorId)
    {
        try {
            $vendor = $this->vendorService->getVendor($vendorId);
            return $this->item($vendor, $this->vendorTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  VendorRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(int $vendorId, VendorRequest $request)
    {
        try {
            $vendor = $this->vendorService->update($request->all(), $vendorId);
            return $this->item($vendor, $this->vendorTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /***
     * Remove the specified resource from storage.
     *
     * @param int $vendorId
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(int $vendorId)
    {
        try {
            $vendor = $this->vendorService->delete($vendorId);
            return $this->returnJsonResponse($vendor);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
