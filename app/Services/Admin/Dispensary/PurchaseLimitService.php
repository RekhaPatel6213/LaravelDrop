<?php

namespace App\Services\Admin\Dispensary;

use App\Models\Repositories\Admin\Dispensary\PurchaseLimitRepository;

class PurchaseLimitService
{
    protected $repository;

    public function __construct(
        PurchaseLimitRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function saveOrUpdate($args)
    {
        $purchase = $this->repository->findWhere(['state' => $args['state']])->first();
        if (null !== $purchase) {
            return $this->repository->update($args, $purchase->id);
        }

        return $this->repository->create($args);
    }

    public function getPurchaseLimit($state)
    {
        if (null == ($purchase = $this->repository->findWhere(['state' => $state])->first())) {
            return ['success' => false, 'message' => __('message.record_not_found')];
        }
        return ['success' => true, 'data' => $purchase];
    }
}
