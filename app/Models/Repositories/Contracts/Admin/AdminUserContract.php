<?php

namespace App\Models\Repositories\Contracts\Admin;

use Prettus\Repository\Contracts\RepositoryInterface;

interface AdminUserContract extends RepositoryInterface
{
    public function list(array $filter, string $sortOn, string $sortOrder);
}
