<?php

namespace App\Http\Traits;

use App\Models\Hub\ProductInventory;
use App\Settings\DispensaryAccessSettings;
use App\Settings\DispensarySettings;

trait ServiceTrait
{
    private function getSortOn($request, $columns = [])
    {
        $sortFields = !empty($columns) ? array_merge(config('constants.SORTABLES'), $columns) : config('constants.SORTABLES');
        $sortFilter = $request->query('sortOn') ?? 'id';

        return isset($sortFields[$sortFilter]) ? $this->alias.'.'.$sortFields[$sortFilter] : $this->alias.'.id';
    }

    public function getInventoryAccess()
    {
        return app(DispensaryAccessSettings::class)->inventory_feature;
    }

    public function getDropkitSetting()
    {
        return app(DispensarySettings::class)->dropkit;
    }

    public function getProductInventoryModelType()
    {
        return  $this->getInventoryAccess() ? ProductInventory::INVENTORY : $this->getInventoryModelType();
    }

    public function getInventoryModelType()
    {
        return  $this->getDropkitSetting() ? ProductInventory::DRIVER : ProductInventory::TERRITORY;
    }

    public function getAuditModelType()
    {
        return  $this->getInventoryAccess() ? ProductInventory::INVENTORY : ProductInventory::TERRITORY;
    }

    public function getInventoryFeatureModelType()
    {
        return  $this->getInventoryAccess() ? $this->getInventoryModelType() : ProductInventory::INVENTORY;
    }

    public function metrcEnable()
    {
        return true;
    }

    public function weedmapEnable()
    {
        return true;
    }

    public function treezEnable()
    {
        return true;
    }
}
