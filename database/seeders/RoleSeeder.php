<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = config('roles.'.config('app.admin_guard'));
        foreach ($roles as $role) {
            Role::create(['name' => $role, 'guard_name' => config('app.admin_guard')]);
        }

        $roles = config('roles.'.config('app.dispensary_guard'));
        foreach ($roles as $role => $permissions) {
            $roleObj = Role::create(['name' => $role, 'guard_name' => config('app.dispensary_guard')]);
            foreach ($permissions as $permission) {
                $permissionObj = Permission::create(['name' => $permission, 'guard_name' => config('app.dispensary_guard'), 'type' => $role]);
            }
        }
    }
}
