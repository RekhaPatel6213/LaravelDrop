<?php

namespace App\Models\Repositories\Territory;

use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Territory\TerritoryInterface;
use App\Models\Territory\Territory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class TerritoryRepository.
 *
 * @package namespace App\Models\Repositories\Territory;
 */
class TerritoryRepository extends BaseRepository implements TerritoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Territory::class;
    }

    public function list(string $search, string $sortOn, string $sortOrder)
    {
        return $this->getQueryBuilder($this->model, $search, $sortOn, $sortOrder)
                  ->with(['territoryModule.module','inventoryModules'])
                  ->get();
    }

    public function getAjaxTerritories()
    {
        return $this->model->orderBy('name')->pluck('name','id');
    }

    public function getAllTerritoryIds(): array
    {
        $query = $this->model->select(DB::raw('group_concat(id) as territoryIds'))->first();
        return $query->territoryIds ? explode(',', $query->territoryIds) : [];
    }

    public function getHourSetTerritoryIds(int $hourSetId): array
    {
        $query = $this->model->select(DB::raw('group_concat(id) as territoryIds'))
            ->where('hour_set_id', $hourSetId)->first();
        return $query->territoryIds ? explode(',', $query->territoryIds) : [];
    }

    public function clearAllPhoneNumbers()
    {
        if (tenant('id')) {
            return $this->model->where('dispensary_id', tenant('id'))->update(['phone' => null]);
        }

        return false;
    }
}
