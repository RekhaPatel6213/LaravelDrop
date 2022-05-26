<?php

namespace App\Models\Admin;

use App\Http\Traits\DispensaryTrait;
use Spatie\LaravelSettings\Models\SettingsProperty as BaseSettingsProperty;

class SettingsProperty extends BaseSettingsProperty
{
    use DispensaryTrait;

    public const DROPKIT = 'dropkit';
}
