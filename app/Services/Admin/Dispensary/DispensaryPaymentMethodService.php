<?php

namespace App\Services\Admin\Dispensary;

use App\Models\Repositories\Admin\Dispensary\DispensaryPaymentMethodRepository;

class DispensaryPaymentMethodService
{
    protected $repository;

    public function __construct(
        DispensaryPaymentMethodRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function store($args, $methodId = null)
    {
        $paymentMethod = null;
        if ($methodId !== null) {
            $paymentMethod = $this->repository->find($methodId);
        }

        return $this->repository->store($args, $paymentMethod);
    }


    public function getAllPaymentMethods()
    {
        return $this->repository->get();
    }

    public function updatePaymentMethods($args)
    {
        $paymentMethods = $args['payment_methods'];
        foreach ($paymentMethods as $paymentMethod) {
            $method = $this->repository->find($paymentMethod['id']);
            if ($method->payment_slug !== 'cod') {
                unset($paymentMethod['enable_cash']);
            }
            $this->repository->store($paymentMethod, $method);
        }

        return $this->repository->get();
    }
}
