<?php

namespace App\Subscribers\Hub;

use App\Events\Hub\DealCreated;
use App\Models\Hub\Deal;
use App\Models\Hub\Product;
use App\Models\Repositories\Hub\DealModelRepository;
use App\Models\Repositories\Hub\ProductRepository;

class ProductSubscriber
{
    protected $repository;
    protected $dealModelRepository;

    public function __construct(ProductRepository $repository, DealModelRepository $dealModelRepository)
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
            'model_type' => Product::class,
        ]);

        $includeIds = $args['applied_products'] ?? [];
        foreach ($includeIds as $productId) {
            $product = $this->repository->find($productId);
            $product->dealModels()->save($deal, ['type' => Deal::INCLUDE, 'sub_type' => Deal::APPLIED]);
        }

        $conditionalIds = $args['condition_products'] ?? [];
        foreach ($conditionalIds as $productId) {
            $product = $this->repository->find($productId);
            $product->dealModels()->save($deal, ['type' => Deal::INCLUDE, 'sub_type' => Deal::CONDITIONAL]);
        }

        $excludeIds = $args['exclude_products'] ?? [];
        foreach ($excludeIds as $productId) {
            $product = $this->repository->find($productId);
            $product->dealModels()->save($deal, ['type' => Deal::EXCLUDE, 'sub_type' => Deal::APPLIED]);
            $product->dealModels()->save($deal, ['type' => Deal::EXCLUDE, 'sub_type' => Deal::CONDITIONAL]);
        }
    }


    public function subscribe($events)
    {
        $events->listen(
            DealCreated::class,
            'App\Subscribers\Hub\ProductSubscriber@handleDealCreated'
        );

    }
}
