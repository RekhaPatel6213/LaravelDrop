<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Guard;

class Permission extends SpatiePermission
{
    protected $casts = [
        'type' => 'string'
    ];

    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $permission = parent::getPermission(['name' => $attributes['name'], 'guard_name' => $attributes['guard_name'], 'type' => $attributes['type']]);

        if ($permission) {
            throw PermissionAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        return parent::query()->create($attributes);
    }
}
