<?php

namespace App\Models\Repositories\Driver;

use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Driver\DriverUserInterface;
use App\Models\Driver\DriverUser;
use Doctrine\DBAL\Driver;
use Illuminate\Support\Facades\DB;

/**
 * Class DriverRepository.
 *
 * @package namespace App\Models\Repositories\DriverUser;
 */
class DriverUserRepository extends BaseRepository implements DriverUserInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DriverUser::class;
    }

    public function getListingDataJoin(string $searchString, string $sortOn, string $sortOrder, $dispensaryId)
    {
        $alias = 'driver_users';
        $tmAlias = 'territory_modules';
        $talias = 'territories';

        $query = $this->model->select(
            $alias . '.id',
            $alias . '.first_name',
            $alias . '.last_name',
            $alias . '.email',
            $alias . '.phone',
            DB::raw('group_concat(' . $talias . '.name' . ') as territory_names')
        );
        $query->leftJoin($tmAlias, function ($join) use ($alias, $tmAlias) {
            $join->on($alias .'.id', '=', $tmAlias . '.module_id')
           ->where($tmAlias . '.module', DriverUser::class);
        });
        $query->join($talias, function ($tjoin) use ($talias, $tmAlias, $dispensaryId) {
            $tjoin->on($tmAlias . '.territory_id', '=', $talias . '.id')
            ->where($talias . '.dispensary_id', $dispensaryId);
        });
        if (!empty($searchString)) {
            foreach (DriverUser::SEARCH_FIELDS as $field) {
                $query->orWhere($field, 'LIKE', '%' . $searchString . '%');
            }
            $query->orWhereRaw('CONCAT(`first_name`, \' \', `last_name`) LIKE \'%' . $searchString . '%\'');
        }
        $query->where($alias . '.dispensary_id', $dispensaryId);
        $query->groupBy($tmAlias . '.module_id');
        return $query->orderBy($sortOn, $sortOrder)->get();
    }


    public function getListingData(string $searchString, string $sortOn, string $sortOrder)
    {
        $alias = 'driver_users';

        $query = $this->model->select(
            $alias . '.id',
            $alias . '.first_name',
            $alias . '.last_name',
            $alias . '.email',
            $alias . '.phone',
            $alias . '.status'
        );

        if (!empty($searchString)) {
            foreach (DriverUser::SEARCH_FIELDS as $field) {
                $query->orWhere($field, 'LIKE', '%' . $searchString . '%');
            }
            $query->orWhereRaw('CONCAT(`first_name`, \' \', `last_name`) LIKE \'%' . $searchString . '%\'');
        }
        return $query->orderBy($sortOn, $sortOrder)->get();
    }
}
