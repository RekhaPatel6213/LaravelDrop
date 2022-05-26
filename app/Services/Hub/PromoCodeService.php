<?php

namespace App\Services\Hub;

use App\Models\Hub\PromoCode;
use App\Models\Repositories\Hub\ProductRepository;
use App\Models\Repositories\Hub\PromoCodeRepository;
use Illuminate\Support\Facades\Auth;

class PromoCodeService
{
    protected $repository;
    protected $productRepository;
    protected $alias;

    public function __construct(
        PromoCodeRepository $repository,
        ProductRepository $productRepository
    ) {
        $this->repository = $repository;
        $this->productRepository = $productRepository;
        $this->alias = 'promo_codes';
    }

    public function list($request)
    {
        $sortOn = $request->query('sortOn', $this->alias . '.created_at');
        $sortOrder = $request->query('sort', PromoCode::DEFAULT_LIST_ORDER);
        $status = $request->query('status', PromoCode::DEFAULT_LIST_STATUS);
        $searchString = $request->query('search', '');

        return $this->repository->getListingData($searchString, $sortOn, $sortOrder, $status);
    }

    public function find(int $promoId)
    {
        return $this->repository->find($promoId);
    }

    public function store($args, $modelId = null)
    {
        $overView = $this->getOverviewText($args);
        $model = null;
        if ($modelId !== null) {
            $model = $this->repository->find($modelId);
        }
        $args['added_by'] = Auth()->user()->id;
        $args['promo_overview'] = $overView;
        return $this->repository->store($args, $model);
    }

    public function update($args, $modelId)
    {
        return $this->repository->update($args, $modelId);
    }

    public function getOverviewText($request)
    {
        $onText = 'entire order';
        if ($request['applies_to'] == PromoCode::PRODUCT) {
            $product = $this->productRepository->getColumn($request['product_id'], 'name');
            $onText = $product->name;
        }

        if ($request['discount_type'] == PromoCode::PERCENTAGE) {
            return 'Get ' . $request['discount_value'] . '% off of ' . $onText;
        }

        return '$' . $request['discount_value'] . ' off of ' . $onText;
    }

    public function delete(int $promoId)
    {
        return $this->repository->delete($promoId);
    }
}
