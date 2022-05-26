<?php

namespace App\Services\Hub;

use App\Models\Hub\Banner;
use App\Models\Repositories\Hub\BannerRepository;
use App\Http\Traits\MediaTrait;
use App\Http\Traits\DispensaryTrait;
use Illuminate\Support\Facades\DB;
use App\Models\Hub\Product;
use App\Models\Hub\Deal;

class BannerService
{
    use DispensaryTrait, MediaTrait;
    private $bannerRepository;
    private $alias;

    public function __construct(BannerRepository $bannerRepository) {
        $this->bannerRepository = $bannerRepository;
        $this->alias = 'banners';
        $this->dispensaryId = tenant('id');
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getListing($request)
    {
        $sortOn = $request->query('sortOn', $this->alias . '.created_at');
        $sortOrder = $request->query('sort', Banner::DEFAULT_LIST_ORDER);
        $searchString = $request->query('search', '');

        return $this->bannerRepository->getListingData($searchString, $sortOn, $sortOrder);
    }

    /**
     * @param $requestData
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function save($requestData)
    {
        $requestData['days'] = explode(',',$requestData['days']);
        $banner = $this->bannerRepository->create($requestData);
        $this->createMedia($banner, 'banner_image');
        return $banner;
    }

    /**
     * @param int $bannerId
     * @return mixed
     */
    public function getBanner(int $bannerId)
    {
        return $this->bannerRepository->find($bannerId);
    }

    /**
     * @param $requestData
     * @param $bannerId
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($requestData, $bannerId)
    {
        $banner = $this->bannerRepository->update($requestData, $bannerId);
        $this->createMedia($banner, 'banner_image');
        return $banner;
    }

    /**
     * @param int $bannerId
     * @return array
     */
    public function delete(int $bannerId)
    {
        $this->bannerRepository->delete($bannerId);
        return ['message' => __('message.deleteSuccess', ['name' => __('Banner')])];
    }

    public function getRedirectDetail(array $requestData)
    {
        $redirectDetail = [];
        $redirectType = $requestData['redirect_type'];
        if(isset($redirectType)) {
            if($redirectType === Banner::PRODUCT){
                $redirectDetail[] = Product::select(DB::raw("CONCAT(products.slug,'-',products.sku) as name"))->pluck('name');
            }else if($redirectType === Banner::DEAL) {
                $redirectDetail[] = Deal::select(DB::raw("CONCAT(deals.slug,'-',deals.sku) as name"))->pluck('name');
            }else{
                $redirectDetail[] = DB::table(strtolower($redirectType))->pluck('name');
            }
        }
        return $redirectDetail;
    }
}
