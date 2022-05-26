<?php

namespace App\Events\Admin;

use App\Models\Admin\AdminUser;

class ResetPassword
{
    public function __construct(AdminUser $adminUser, $token)
    {
        $this->adminUser = $adminUser;
        $this->token = $token;
    }
}
