<?php

namespace App\Services\Admin\Dispensary;

use App\Models\Repositories\Admin\Dispensary\DropOffOptionRepository;

class DropOffOptionService
{
    protected $repository;

    public function __construct(
        DropOffOptionRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function getDropOffOptions()
    {
        return $this->repository->get();
    }

    public function store($args, $dropOffId = null)
    {
        $option = null;
        if ($dropOffId !== null) {
            $option = $this->repository->find($dropOffId);
        }
        return $this->repository->store($args, $option);
    }
}
