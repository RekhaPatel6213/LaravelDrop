<?php

namespace App\Subscribers\Hub;

use App\Events\Hub\DealCreated;
use App\Models\Brand\Brand;
use App\Models\Hub\Deal;
use App\Models\Hub\Product;
use App\Models\Repositories\Brand\BrandRepository;
use App\Models\Repositories\Hub\DealModelRepository;
use App\Models\Repositories\Hub\ProductRepository;

class BrandSubscriber
{
    protected $repository;
    protected $dealModelRepository;

    public function __construct(BrandRepository $repository, DealModelRepository $dealModelRepository)
    {
        $this->repository = $repository;
        $this->dealModelRepository = $dealModelRepository;
    }

    /**
     * Handle the event.
     *
     * @param  DealCreated  $event
     * @return void
     */
    public function handleDealCreated(DealCreated $event)
    {
        $deal = $event->deal;
        $args = $event->args;

        $this->dealModelRepository->deleteWhere([
            'deal_id' => $deal->id,
            'model_type' => Brand::class,
        ]);

        $includeIds = $args['applied_brands'] ?? [];
        foreach ($includeIds as $brandId) {
            $brand = $this->repository->find($brandId);
            $brand->dealModels()->save($deal, ['type' => Deal::INCLUDE, 'sub_type' => Deal::APPLIED]);
        }

        $conditionalIds = $args['condition_brands'] ?? [];
        foreach ($conditionalIds as $brandId) {
            $brand = $this->repository->find($brandId);
            $brand->dealModels()->save($deal, ['type' => Deal::INCLUDE, 'sub_type' => Deal::CONDITIONAL]);
        }

        $excludeIds = $args['exclude_brands'] ?? [];
        foreach ($excludeIds as $brandId) {
            $brand = $this->repository->find($brandId);
            $brand->dealModels()->save($deal, ['type' => Deal::EXCLUDE, 'sub_type' => Deal::APPLIED]);
            $brand->dealModels()->save($deal, ['type' => Deal::EXCLUDE, 'sub_type' => Deal::CONDITIONAL]);
        }
    }


    public function subscribe($events)
    {
        $events->listen(
            DealCreated::class,
            'App\Subscribers\Hub\BrandSubscriber@handleDealCreated'
        );

    }
}
