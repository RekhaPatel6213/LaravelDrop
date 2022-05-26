<?php

namespace App\Services\Admin\Dispensary;

use App\Models\Repositories\Admin\Dispensary\DispensaryCustomFeeRepository;

class DispensaryCustomFeeService
{
    protected $repository;

    public function __construct(
        DispensaryCustomFeeRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function store($args, $customFeeId = null)
    {
        $customFee = null;
        if ($customFeeId !== null) {
            $customFee = $this->repository->find($customFeeId);
        }

        return $this->repository->store($args, $customFee);
    }


    public function getAllCustomFees()
    {
        return $this->repository->get(['id', 'title', 'description', 'fee_amount']);
    }

    public function delete(int $orderFeeId)
    {
        return $this->repository->delete($orderFeeId);
    }

    public function updateCustomFees($args)
    {
        $customFees = $args['data'];
        foreach ($customFees as $customFee) {
            $method = $this->repository->find($customFee['id']);
            $this->repository->store($customFee, $method);
        }

        return $this->repository->get();
    }
}
