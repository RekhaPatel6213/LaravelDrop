<?php

namespace App\Services\Admin\Dispensary;

use App\Models\Admin\Dispensary\LoyaltyProgram;
use App\Models\Repositories\Admin\Customer\DispensaryCustomerRepository;
use App\Models\Repositories\Admin\Dispensary\LoyaltyProgramRepository;

class LoyaltyProgramService
{
    protected $repository;
    protected $dCustomerRepository;
    protected $alias;

    public function __construct(
        LoyaltyProgramRepository $repository,
        DispensaryCustomerRepository $dCustomerRepository
    ) {
        $this->repository = $repository;
        $this->dCustomerRepository = $dCustomerRepository;
        $this->alias = 'loyalty_programs';
    }

    public function list($request)
    {
        $sortFields = [
            'name' => $this->alias . '.name'
        ];
        $sortFilter = $request->query('sortOn') ?? 'id';
        $sortOn = $sortFields[$sortFilter] ?? $this->alias . '.id';

        $sortOrder = $request->query('sort', LoyaltyProgram::DEFAULT_LIST_ORDER);
        return $this->repository->getListingData($sortOn, $sortOrder);
    }

    public function getDefaults()
    {
        $defaults = $this->repository->getDefaults();
        foreach ($defaults as $default) {
            $data[$default->type] = [
                'id' => $default->id,
                'name' => $default->name,
                'points' => $default->points,
                'status' => $default->status,
            ];
        }
        return $data;
    }

    public function updateDefaults($args)
    {
        $defaults = $args['defaults'];
        foreach ($defaults as $data) {
            $id = $data['id'];
            unset($data['id']);
            $this->repository->update($data, $id);
        }
    }


    public function find(int $promoId)
    {
        return $this->repository->find($promoId);
    }

    public function store($args, $modelId = null)
    {
        $model = null;
        if ($modelId !== null) {
            $model = $this->repository->find($modelId);
        } else {
            $valid = $this->checkDispensarySmsBalance();
            if (!$valid) {
                return ['success' => false, 'message' => __('message.out_credit_loyalty')];
            }
        }
        $program = $this->repository->store($args, $model);
        return ['success' => true, 'data' => $program];
    }

    public function update($args, $modelId)
    {
        return $this->repository->update($args, $modelId);
    }

    public function delete(int $programId)
    {
        return $this->repository->delete($programId);
    }

    protected function checkDispensarySmsBalance()
    {
        $totalCustomers = $this->dCustomerRepository->getSmsOptedCustomersCount();
        $availableSmsLimit = tenant()->balance;
        return $availableSmsLimit >= $totalCustomers;
    }
}
