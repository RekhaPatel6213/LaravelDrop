<?php

namespace App\Services\Hub;

use App\Models\Hub\Vendor;
use App\Models\Repositories\Hub\VendorRepository;
use App\Http\Traits\DispensaryTrait;

class VendorService
{
    use DispensaryTrait;
    private $vendorRepository;
    private $alias;

    /**
     * VendorService constructor.
     * @param VendorRepository $vendorRepository
     */
    public function __construct(VendorRepository $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
        $this->alias = 'vendors';
        $this->dispensaryId = tenant('id');
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getListing($request)
    {
        $sortOn = $request->query('sortOn', $this->alias . '.created_at');
        $sortOrder = $request->query('sort', Vendor::DEFAULT_LIST_ORDER);
        $searchString = $request->query('search', '');

        return $this->vendorRepository->getListingData($searchString, $sortOn, $sortOrder);
    }

    /**
     * @param $request
     * @return array
     */
    public function save($requestData)
    {
        return $this->vendorRepository->create($requestData);
    }

    /**
     * @param int $vendorId
     * @return mixed
     */
    public function getVendor(int $vendorId)
    {
        return $this->vendorRepository->find($vendorId);
    }

    /**
     * @param $request
     * @param $vendorId
     * @return array
     */
    public function update($requestData, $vendorId)
    {
        return $this->vendorRepository->update($requestData, $vendorId);
    }

    /**
     * @param int $vendorId
     * @return array
     */
    public function delete(int $vendorId)
    {
        $this->vendorRepository->delete($vendorId);
        return ['data' => ['message' => "Vendor Deleted Successfully"]];
    }
}
