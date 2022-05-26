<?php

namespace App\Models\Repositories\Admin\Dispensary;

use App\Models\Admin\Dispensary\DispensaryUser;
use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Admin\Dispensary\DispensaryUserRepository as DispensaryUserInterface;
use App\Models\Permission;

/**
 * Class DispensaryUserRepository.
 *
 * @package namespace App\Models\Repositories\Admin\Dispensary;
 */
class DispensaryUserRepository extends BaseRepository implements DispensaryUserInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DispensaryUser::class;
    }

    /**
     * Dispensary User listing data
     *
     * @return mixed
     */
    public function getListingData(string $searchString, string $sortOn, string $sortOrder)
    {
        $query = $this->model->select('*');
        if (!empty($searchString)) {
            foreach (DispensaryUser::SEARCH_FIELDS as $field) {
                $query->orWhere('dispensary_users.' . $field, 'LIKE', '%' . $searchString . '%');
            }
        }
        return $query->orderBy($sortOn, $sortOrder)->get();
    }

    public function setPermission(DispensaryUser $dispensaryUser)
    {
        $permissions = Permission::pluck('id');
        $dispensaryUser->permissions()->sync($permissions);
    }
}
