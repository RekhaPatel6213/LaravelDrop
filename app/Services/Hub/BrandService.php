<?php

namespace App\Services\Hub;

use App\Models\Repositories\Brand\BrandRepository;

class BrandService
{
    protected $repository;
    protected $alias;

    public function __construct(
        BrandRepository $repository
    ) {
        $this->repository = $repository;
        $this->alias = 'brands';
    }



    public function brandList($search = null)
    {
        return $this->repository->brandList($search);
    }
}
