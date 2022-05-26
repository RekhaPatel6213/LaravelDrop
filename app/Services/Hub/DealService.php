<?php

namespace App\Services\Hub;

use App\Events\Hub\DealCreated;
use App\Models\Hub\Deal;
use App\Models\Repositories\Hub\DealRepository;
use App\Models\Repositories\Hub\ProductRepository;
use App\Http\Traits\DispensaryTrait;

class DealService
{
    use DispensaryTrait;
    protected $repository;
    protected $productRepository;
    protected $alias;
    private $sku;

    public function __construct(
        DealRepository $repository,
        ProductRepository $productRepository
    ) {
        $this->repository = $repository;
        $this->productRepository = $productRepository;
        $this->alias = 'deals';
        $this->sku = $this->generateUniqueId('Hub\Deal', 'sku');
    }

    public function list($request)
    {
        $sortOn = $request->query('sortOn', $this->alias . '.created_at');
        $sortOrder = $request->query('sort', Deal::DEFAULT_LIST_ORDER);
        $status = $request->query('status', Deal::DEFAULT_LIST_STATUS);
        $searchString = $request->query('search', '');

        return $this->repository->getListingData($searchString, $sortOn, $sortOrder, $status);
    }

    public function find(int $dealId)
    {
        return $this->repository->find($dealId);
    }

    public function store($args, $modelId = null)
    {
        $model = null;
        if ($modelId !== null) {
            $model = $this->repository->find($modelId);
        }
        $args['sku'] = (!isset($args['sku'])) ? $this->sku : $args['sku'];
        $args['added_by'] = Auth()->user()->id;
        $deal = $this->repository->store($args, $model);
        event(new DealCreated($deal, $args));
        return $deal;
    }

    public function update($args, $modelId)
    {
        return $this->repository->update($args, $modelId);
    }

    public function delete(int $dealId)
    {
        return $this->repository->delete($dealId);
    }
}
