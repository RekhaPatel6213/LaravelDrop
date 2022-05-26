<?php

namespace App\Subscribers\Hub;

use App\Events\Hub\DealCreated;
use App\Models\Hub\Deal;
use App\Models\Hub\ProductVariant;
use App\Models\Repositories\Hub\CategoryRepository;
use App\Models\Repositories\Hub\DealModelRepository;

class CategorySubscriber
{
    protected $repository;
    protected $dealModelRepository;

    public function __construct(CategoryRepository $repository, DealModelRepository $dealModelRepository)
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

        $includeIds = $args['applied_categories'] ?? [];
        foreach ($includeIds as $variantId) {
            $variant = $this->repository->find($variantId);
            $variant->dealModels()->save($deal, ['type' => Deal::INCLUDE , 'sub_type' => Deal::APPLIED]);
        }

        $conditionIds = $args['condition_categories'] ?? [];
        foreach ($conditionIds as $variantId) {
            $variant = $this->repository->find($variantId);
            $variant->dealModels()->save($deal, ['type' => Deal::INCLUDE , 'sub_type' => Deal::CONDITIONAL]);
        }

        $excludeIds = $args['exclude_categories'] ?? [];
        foreach ($excludeIds as $variantId) {
            $variant = $this->repository->find($variantId);
            $variant->dealModels()->save($deal, ['type' => Deal::EXCLUDE , 'sub_type' => Deal::APPLIED]);
            $variant->dealModels()->save($deal, ['type' => Deal::EXCLUDE , 'sub_type' => Deal::CONDITIONAL]);
        }
    }


    public function subscribe($events)
    {
        $events->listen(
            DealCreated::class,
            'App\Subscribers\Hub\CategorySubscriber@handleDealCreated'
        );

    }
}
