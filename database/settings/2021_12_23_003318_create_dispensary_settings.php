<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateDispensarySettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('hub_setting.email_notifications', []);
        $this->migrator->add('hub_setting.sms_notifications', []);
    }
}
