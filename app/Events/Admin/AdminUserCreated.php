<?php

namespace App\Events\Admin;

use App\Models\Admin\AdminUser;

class AdminUserCreated
{
    public $adminUser;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AdminUser $adminUser)
    {
        $this->adminUser = $adminUser;
    }
}
