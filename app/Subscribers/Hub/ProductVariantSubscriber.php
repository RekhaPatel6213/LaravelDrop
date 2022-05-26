<?php

namespace App\Subscribers\Hub;

use App\Events\Hub\DealCreated;
use App\Models\Hub\Deal;
use App\Models\Hub\ProductVariant;
use App\Models\Repositories\Hub\DealModelRepository;
use App\Models\Repositories\Hub\ProductVariantRepository;

class ProductVariantSubscriber
{
    protected $repository;
    protected $dealModelRepository;

    public function __construct(ProductVariantRepository $repository, DealModelRepository $dealModelRepository)
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
            'model_type' => ProductVariant::class,
        ]);

        $includeIds = $args['applied_variants'] ?? [];
        foreach ($includeIds as $variantId) {
            $variant = $this->repository->find($variantId);
            $variant->dealModels()->save($deal, ['sub_type' => Deal::APPLIED]);
        }

        $excludeIds = $args['condition_variants'] ?? [];
        foreach ($excludeIds as $variantId) {
            $variant = $this->repository->find($variantId);
            $variant->dealModels()->save($deal, ['sub_type' => Deal::CONDITIONAL]);
        }
    }


    public function subscribe($events)
    {
        $events->listen(
            DealCreated::class,
            'App\Subscribers\Hub\ProductVariantSubscriber@handleDealCreated'
        );

    }
}
