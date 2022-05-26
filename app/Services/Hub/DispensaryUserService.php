<?php

namespace App\Services\Hub;

use App\Events\Hub\DispensaryUserEvent;
use App\Models\Admin\Dispensary\DispensaryUser;
use App\Models\Repositories\Admin\Dispensary\DispensaryUserRepository;
use App\Http\Traits\DispensaryTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;

class DispensaryUserService
{
    use DispensaryTrait;
    private $dispensaryUserRepository;
    private $alias;
    private $dispensaryId;

    public function __construct(DispensaryUserRepository $dispensaryUserRepository) {
        $this->dispensaryUserRepository = $dispensaryUserRepository;
        $this->alias = 'dispensary_users';
        $this->dispensaryId = tenant('id');
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getListing($request)
    {
        $sortOn = $request->query('sortOn', $this->alias . '.created_at');
        $sortOrder = $request->query('sort', DispensaryUser::DEFAULT_LIST_ORDER);
        $searchString = $request->query('search', '');

        return $this->dispensaryUserRepository->getListingData($searchString, $sortOn, $sortOrder);
    }

    /**
     * @param $requestData
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function save($requestData)
    {
        $dispensaryUser = $this->dispensaryUserRepository->create($requestData);
        $dispensaryUser->permissions()->sync($requestData['permissions']);
        event(new DispensaryUserEvent($dispensaryUser, $requestData));
        return $dispensaryUser;
    }

    /**
     * @param int $dispensaryUserId
     * @return mixed
     */
    public function getDispensaryUser(int $dispensaryUserId)
    {
        return $this->dispensaryUserRepository->find($dispensaryUserId);
    }

    /**
     * @param $request
     * @return array
     */
    public function getPermission($request)
    {
        $permissionObject = [];
        if(isset($request['type'])) {
            $permissions = Permission::whereIn('guard_name', $request['type'])->get();
            foreach ($request['type'] as $type) {
                $permissionObject[$type] = $permissions->where('guard_name', $type)->pluck('name', 'id');
            }
        }
        return $permissionObject;
    }

    /**
     * @param $requestData
     * @param $dispensaryUserId
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($requestData, $dispensaryUserId)
    {
        $dispensaryUser = $this->dispensaryUserRepository->update($requestData, $dispensaryUserId);

        if(isset($requestData['permissions'])){
            $dispensaryUser->permissions()->sync($requestData['permissions']);
        }

        $isUpdated = $dispensaryUser->getChanges();
        if(isset($isUpdated['role'])){
            event(new DispensaryUserEvent($dispensaryUser, $requestData));
        }
        return $dispensaryUser;
    }

    /**
     * @param int $dispensaryUserId
     * @return array
     */
    public function delete(int $dispensaryUserId)
    {
        $this->dispensaryUserRepository->delete($dispensaryUserId);
        return ['message' => __('message.deleteSuccess', ['name' => __('dispensary.dispensaryUser')])];
    }
}