<?php

namespace App\Services\Admin\Dispensary;

use App\Models\Admin\Dispensary\TaxesCostsSetting;
use App\Models\Repositories\Admin\Dispensary\TaxesCostsSettingRepository;
use App\Models\Repositories\Territory\TerritoryModuleRepository;

class TaxesCostsSettingService
{
    protected $repository;
    protected $moduleRepository;
    protected $alias;

    public function __construct(
        TaxesCostsSettingRepository $repository,
        TerritoryModuleRepository $moduleRepository
    ) {
        $this->repository = $repository;
        $this->moduleRepository = $moduleRepository;
        $this->alias = 'taxes_costs_settings';
        $this->territoryAlias = 'territories';
        $this->locationAlias = 'locations';
    }

    public function getTaxListing($request)
    {
        $sortFields = [
            'state_tax' => $this->alias . '.state_tax',
            'local_tax' => $this->alias . '.local_tax',
            'excise_tax' => $this->alias . '.excise_tax',
            'cannabis_tax_medical' => $this->alias . '.cannabis_tax_medical',
            'cannabis_tax_adult' => $this->alias . '.cannabis_tax_adult',
            'name' => $this->territoryAlias. '.name',
            'city' => $this->locationAlias . '.city',
            'zip_code' => $this->locationAlias . '.zip_code',
        ];
        $sortFilter = $request->query('sortOn') ?? 'id';
        $sortOn = $sortFields[$sortFilter] ?? $this->alias . '.id';

        $sortOrder = $request->query('sort', TaxesCostsSetting::DEFAULT_LIST_ORDER);
        $taxAndCostsData = $this->moduleRepository->getTaxListingData($sortOn, $sortOrder)->toArray();
        $response = [];
        foreach ($taxAndCostsData as $data) {
            $response[$data['territory_id']]['territory_id'] = $data['territory_id'];
            $response[$data['territory_id']]['territory_name'] = $data['name'];
            $response[$data['territory_id']]['data'][] = [
                'location_id' => $data['location_id'],
                'city' => $data['city'],
                'zip_code' => $data['zip_code'],
                'state_tax' => $data['state_tax'],
                'local_tax' => $data['local_tax'],
                'excise_tax' => $data['excise_tax'],
                'cannabis_tax_medical' => $data['cannabis_tax_medical'],
                'cannabis_tax_adult' => $data['cannabis_tax_adult'],
            ];

        }

        return $response;
    }

    public function updateTaxesCosts($args)
    {
        $where = [
            'territory_id' => $args['territory_id'],
            'location_id' => $args['location_id']
        ];
        unset($args['territory_id'],$args['location_id']);
        $this->repository->updateOrCreate($where, $args);
        $taxAndCost = $this->repository->findWhere($where);
        return $taxAndCost;
    }

    public function getDeliveryListing($request)
    {
        $sortFields = [
            'name' => $this->territoryAlias. '.name',
            'city' => $this->locationAlias . '.city',
            'zip_code' => $this->locationAlias . '.zip_code',

        ];
        $sortFilter = $request->query('sortOn') ?? 'id';
        $sortOn = $sortFields[$sortFilter] ?? $this->alias . '.id';

        $sortOrder = $request->query('sort', TaxesCostsSetting::DEFAULT_LIST_ORDER);
        $taxAndCostsData = $this->moduleRepository->getDeliveryListingData($sortOn, $sortOrder)->toArray();

        $response = [];
        foreach ($taxAndCostsData as $data) {
            $response[$data['territory_id']]['territory_id'] = $data['territory_id'];
            $response[$data['territory_id']]['territory_name'] = $data['name'];
            $response[$data['territory_id']]['data'][] = [
                'location_id' => $data['location_id'],
                'city' => $data['city'],
                'zip_code' => $data['zip_code'],
                'minimum_order_cost' => $data['minimum_order_cost'],
                'cost_for_free_delivery' => $data['cost_for_free_delivery'],
                'delivery_fee' => $data['delivery_fee'],
            ];

        }

        return $response;
    }

    public function store($request, $taxAndCostsId = null)
    {
        try {
            $requestData = $request->all();
            $model = null;
            if ($taxAndCostsId !== null) {
                $model = $this->repository->find($taxAndCostsId);
            }
            $taxAndCost = $this->repository->store($requestData, $model);

            return ['success' => true, 'data' => $taxAndCost];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
