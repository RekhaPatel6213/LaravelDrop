<?php

namespace App\Models\Repositories\Territory;

use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Territory\TerritoryModuleInterface;
use App\Models\Territory\TerritoryModule;

/**
 * Class TerritoryModuleRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Territory;
 */
class TerritoryModuleRepository extends BaseRepository implements TerritoryModuleInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TerritoryModule::class;
    }

    public function isLocationAlreadyAdded(array $locationIds, array $territoryIds)
    {
        $locations = [];
        $moduleData = $this->model->whereIntegerInRaw('module_id', $locationIds)
            ->whereIntegerInRaw('territory_id', $territoryIds)
            ->where('module_type', TerritoryModule::LOCATION)->get();
        foreach ($moduleData as $data) {
            $locations[] = $data->module_id;
        }

        return $locations;
    }

    public function getTerritoryIdsForModule($module, $moduleId, $territoryIds)
    {
        return $this->model->select('territory_id')
            ->where('module_type', $module)
            ->where('module_id', $moduleId)
            ->whereIntegerInRaw('territory_id', $territoryIds)->pluck('territory_id')->toArray();
    }

    public function getTaxListingData(string $sortOn, string $sortOrder)
    {
        $query = $this->model->select(
            'territories.id as territory_id',
            'territories.name',
            'territories.type',
            'territory_modules.id as location_id',
            'locations.zip_code',
            'locations.city',
            'taxes_costs_settings.state_tax',
            'taxes_costs_settings.local_tax',
            'taxes_costs_settings.excise_tax',
            'taxes_costs_settings.cannabis_tax_medical',
            'taxes_costs_settings.cannabis_tax_adult'
        )
            ->join('locations', 'territory_modules.module_id', '=', 'locations.id')
            ->leftJoin('territories', 'territory_modules.territory_id', '=', 'territories.id')
            ->leftjoin('taxes_costs_settings', function ($join) {
                $join->on('locations.id', '=', 'taxes_costs_settings.location_id');
                $join->on('territory_modules.territory_id', '=', 'taxes_costs_settings.territory_id');
            });

        $query->where('territory_modules.module_type', TerritoryModule::LOCATION);
        return $query->orderBy($sortOn, $sortOrder)->get();
    }

    public function getDeliveryListingData(string $sortOn, string $sortOrder)
    {
        $query = $this->model->select(
            'territories.id as territory_id',
            'territories.name',
            'locations.zip_code',
            'locations.city',
            'territory_modules.id as location_id',
            'taxes_costs_settings.minimum_order_cost',
            'taxes_costs_settings.delivery_fee',
            'taxes_costs_settings.cost_for_free_delivery'
        )
            ->join('locations', 'territory_modules.module_id', '=', 'locations.id')
            ->leftJoin('territories', 'territory_modules.territory_id', '=', 'territories.id')
            ->leftjoin('taxes_costs_settings', function ($join) {
                $join->on('locations.id', '=', 'taxes_costs_settings.location_id');
                $join->on('territory_modules.territory_id', '=', 'taxes_costs_settings.territory_id');
            });

        $query->where('territory_modules.module_type', TerritoryModule::LOCATION);
        return $query->orderBy($sortOn, $sortOrder)->get();
    }
}
