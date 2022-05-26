<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateDispensaryAccessSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('dispensary_access.standalone', false);
        $this->migrator->add('dispensary_access.smart_deals', false);
        $this->migrator->add('dispensary_access.scheduled_delivery', false);
        $this->migrator->add('dispensary_access.iframe_code', false);
        $this->migrator->add('dispensary_access.seo_location', false);
    }
}
