<?php

namespace App\Services\Admin;

use App\Events\Admin\AdminUserCreated;
use App\Events\Admin\AdminUserUpdated;
use App\Models\Admin\AdminUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminService
{
    protected $adminUserRepository;

    public function __construct($adminUserRepository)
    {
        $this->repository = $adminUserRepository;
    }

    public function getAdmin(int $adminId)
    {
        return $this->repository->find($adminId);
    }

    public function storeAdmin($requestData, AdminUser $adminUser = null)
    {
        $isNewAdminUser = false;
        if ($adminUser === null) {
            $isNewAdminUser = true;
        }

        $adminUser = $this->repository->store($requestData, $adminUser, $isNewAdminUser );

        if ($isNewAdminUser === true) {
            event(new AdminUserCreated($adminUser));
        } elseif ($isNewAdminUser === false) {
            event(new AdminUserUpdated($adminUser));
        }
        return $adminUser;
    }

    public function getQueryBuilder($request)
    {
        $filterArray = $request->query();
        $sortOn = $request->query('sortOn', 'admin_users.created_at');
        $sortOrder = $request->query('sort', 'desc');
        return $this->repository->list($filterArray, $sortOn, $sortOrder);
    }

    public function deleteAdmin($adminId)
    {
        $this->repository->delete($adminId);
        return ['message' => __('message.deleteSuccess', ['name' => __('message.adminUser')])];
    }
}
