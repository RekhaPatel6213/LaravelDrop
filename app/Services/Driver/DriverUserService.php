<?php

namespace App\Services\Driver;

use App\Events\Driver\DriverUserCreated;
use App\Events\Driver\DriverUserUpdated;
use App\Http\Traits\ServiceTrait;
use App\Models\Driver\DriverUser;
use App\Models\Repositories\Driver\DriverUserRepository;
use App\Models\Repositories\Territory\TerritoryModuleRepository;
use App\Models\Repositories\Territory\TerritoryRepository;

class DriverUserService
{
    use ServiceTrait;
    protected $driverRepository;
    protected $alias;
    protected $moduleRepository;
    protected $territoryRepository;

    public function __construct(
        DriverUserRepository $driverRepository,
        TerritoryRepository $territoryRepository,
        TerritoryModuleRepository $moduleRepository
    ) {
        $this->driverRepository = $driverRepository;
        $this->territoryRepository = $territoryRepository;
        $this->moduleRepository = $moduleRepository;
        $this->alias = 'driver_users';
    }

    public function getListing($request)
    {
        $sortOn = $this->getSortOn($request, ['name' => 'first_name']);
        $sortOrder = $request->query('sort', DriverUser::DEFAULT_LIST_ORDER);
        $searchString = $request->query('search', '');

        $drivers = $this->driverRepository->getListingData($searchString, $sortOn, $sortOrder);

        $territoryIds = $this->territoryRepository->get(['id'])->pluck('id')->toArray();

        foreach ($drivers as $key => $driver) {
            $terretories = $this->moduleRepository->getTerritoryIdsForModule(DriverUser::class, $driver->id, $territoryIds);
            $terretoryNames = $this->territoryRepository->findWhereIn('id', $terretories)->pluck('name')->toArray();
            $terretoryNames = implode(',', $terretoryNames);
            $drivers[$key]['territory_names'] = $terretoryNames;
        }

        return $drivers;
    }

    public function save($args)
    {
        $driver = $this->driverRepository->create($args);
        event(new DriverUserCreated($driver, $args));
        return $driver;
    }

    public function getDriver(int $driverId)
    {
        return $this->driverRepository->find($driverId);
    }

    public function update($args, $driverId)
    {
        $driver = $this->driverRepository->update($args, $driverId);
        event(new DriverUserUpdated($driver, $driver, $args));
        return $driver;
    }

    public function delete(int $driverId)
    {
        $this->driverRepository->delete($driverId);
        return ['message' => __('message.driver_deleted')];
    }
}
