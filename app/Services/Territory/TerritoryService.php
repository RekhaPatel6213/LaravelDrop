<?php

namespace App\Services\Territory;

use App\Models\Driver\DriverUser;
use App\Models\Location\Location;
use App\Models\Repositories\Driver\DriverUserRepository;
use App\Models\Repositories\Location\LocationRepository;
use App\Models\Repositories\Territory\TerritoryModuleRepository;
use App\Models\Repositories\Territory\TerritoryRepository;
use App\Models\Repositories\Hub\InventoryRepository;
use App\Models\Territory\Territory;
use App\Models\Repositories\Admin\Dispensary\DispensaryUserRepository;
use App\Models\Territory\TerritoryModule;
use App\Jobs\ElasticDispensaryJob;
use App\Http\Traits\OrderTrait;
use App\Models\Hub\ProductInventory;
use App\Models\Hub\ModelInventory;
use App\Services\Territory\TaxesCostsSettingService;

class TerritoryService
{
    use OrderTrait;

    protected $repository;
    protected $alias;
    protected $locationRepository;
    protected $driverRepository;
    protected $moduleRepository;
    protected $inventoryRepository;
    protected $dispensaryUserRepository;
    protected $taxesCostsSettingService;

    public function __construct(
        TerritoryRepository $repository,
        LocationRepository $locationRepository,
        TerritoryModuleRepository $moduleRepository,
        DriverUserRepository $driverRepository,
        InventoryRepository $inventoryRepository,
        DispensaryUserRepository $dispensaryUserRepository,
        TaxesCostsSettingService $taxesCostsSettingService
    ) {
        $this->repository = $repository;
        $this->locationRepository = $locationRepository;
        $this->moduleRepository = $moduleRepository;
        $this->driverRepository = $driverRepository;
        $this->inventoryRepository = $inventoryRepository;
        $this->dispensaryUserRepository = $dispensaryUserRepository;
        $this->taxesCostsSettingService = $taxesCostsSettingService;
        $this->alias = 'territories';
        $this->subalias = 'territory_modules';
    }

    public function list(array $requestData)
    {
        $search = $requestData['search'] ?? '';
        $sortOn = $requestData['sortOn'] ?? 'id';
        $sortOrder = $requestData['sort'] ?? Territory::DEFAULT_LIST_ORDER;
        return $allData = $this->repository->list($search, $sortOn, $sortOrder);
    }

    public function getAjaxTerritories()
    {
        return $this->repository->getAjaxTerritories();
    }

    public function get($id)
    {
        return $this->repository->with(['geoPoints','territoryModule.module','inventoryModules'])->find($id);
    }

    public function save(array $requestData, int $id = null)
    {
        $isNew = false;
        if ($id === null) {
            $isNew = true;
            $territory = $this->repository->create($requestData);
        }

        if ($id) {
            $territory = $this->repository->update($requestData, $id);
        }
        
        $this->saveInventory($territory, $requestData['inventory_id'] ?? null, $isNew);
        $this->saveModule($territory, $requestData['location_ids'] ?? null, TerritoryModule::LOCATION);
        $this->saveModule($territory, $requestData['driver_ids'] ?? null, TerritoryModule::DRIVER);
        $this->saveModule($territory, $requestData['dispensary_user_ids'] ?? null, TerritoryModule::DISPENSARYUSER);
        
        $this->saveGeoPoints($territory, $requestData);

        dispatch(new ElasticDispensaryJob(tenant(), ['Product','Reward']));
        return $territory;
    }

    public function saveModule(Territory $territory, ?array $moduleIds, $type)
    {
        $this->moduleRepository->deleteWhere(['territory_id' => $territory->id, 'module_type' => $type]);
        if ($moduleIds) {
            $repository = TerritoryModule::REPOSITORY[$type].'Repository';
            $modules = $this->$repository->findWhereIn('id', $moduleIds);
            foreach ($modules as $module) {
                $module->territoryModules()->save($territory);
            }
        };
    }

    public function saveInventory(Territory $territory, ?int $inventoryId = null, bool $isNew = true)
    {
        if ($inventoryId !== null) {
            $territoryId = $territory->id;
            $inventory = $this->inventoryRepository->find($inventoryId);
            if ($isNew) {
                $territory->inventoryModules()->save($inventory);
                return true; 
            }

            $this->checkPendingOrders('change', ProductInventory::INVENTORY);
            $modelInventory = ModelInventory::ofModelType(ProductInventory::TERRITORY)->inModelId([$territoryId])->first();
            
            if ($modelInventory === null) {
                $territory->inventoryModules()->save($inventory);
                return true;
            }

            if ($modelInventory->inventory_id !== $inventoryId) {
                $inventoryIds = [$modelInventory->inventory_id];
                $modelInventory->inventory_id !== null ? \array_push($inventoryIds, $inventoryId) : '';
                $modelInventory->inventory_id = $inventoryId;
                $modelInventory->save();

                $this->updateMetrcPackage($inventoryIds);
            }
            return true;
        }
    }

    public function saveGeoPoints(Territory $territory, array $requestData)
    {
        $geoPoints = $requestData['geo_points'] ?? null;

        if($requestData['type'] === Territory::GEO && $geoPoints !== null){
            $territory->geoPoints()->delete();
            foreach($geoPoints as $point){
                $territory->geoPoints()->create(['geo_points' => $point]);
            }
        }
        $requestData['territory_id'] = $territory->id;
        $requestData['location_id'] = null;
        $this->taxesCostsSettingService->updateTaxesCosts($requestData);
    }

    public function updateMetrcPackage(array $inventoryIds)
    {
        //Need to write code When metrc package funcationality add
    }

    public function updatePhoneNumbers($args)
    {
        $phones = $args['data'];
        $mapped = [];
        foreach ($phones as $phone => $territories) {
            foreach ($territories as $territory) {
                $mapped[$territory] = $phone;
            }
        }

        if (!empty($mapped)) {
            $this->repository->clearAllPhoneNumbers();
        }
        foreach ($mapped as $key => $value) {
            $this->repository->update(['phone' => $value], $key);
        }
        $data = $this->getPhoneNumbers();

        return collect($data);
    }

    public function getPhoneNumbers($collection = false)
    {
        $territories = $this->repository->findWhereNotNull('phone');
        $mapped = [];
        foreach ($territories as $territory) {
            $mapped[$territory->phone][] = $territory->id;
        }
        return $collection ? collect($mapped) : $mapped;
    }

    public function delete(int $id)
    {
        $this->moduleRepository->deleteWhere(['territory_id' => $id]);
        ModelInventory::ofModelType(ProductInventory::TERRITORY)->inModelId([$id])->delete();
        $this->repository->delete($id);
        return ['message' => __('message.deleteSuccess', ['name' => __('product.territory')])];
    }
}
