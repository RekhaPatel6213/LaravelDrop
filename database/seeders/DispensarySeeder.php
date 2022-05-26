<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\LaravelSettings\Migrations\SettingsMigrator;

class DispensarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $migrator = app(SettingsMigrator::class);
        $migrator->add('dispensary_access.standalone', false);
        $migrator->add('dispensary_access.smart_deals', false);
        $migrator->add('dispensary_access.scheduled_delivery', false);
        $migrator->add('dispensary_access.iframe_code', false);
        $migrator->add('dispensary_access.seo_location', false);
        $migrator->add('dispensary_access.driver_optimization', false);
        $migrator->add('dispensary_access.inventory_feature', false);
        $migrator->add('hub_setting.email_notifications', []);
        $migrator->add('hub_setting.sms_notifications', []);
        $migrator->add('hub_setting.dropkit', false);
        $migrator->add('hub_setting.app_settings', []);
        $migrator->add('hub_setting.dispatch_settings', []);
        $migrator->add('hub_setting.inventory_settings', []);
        $migrator->add('hub_setting.website_settings', []);
        $migrator->add('hub_setting.customer_verification', []);
        $migrator->add('hub_setting.new_drop_notification', []);
        $migrator->add('hub_setting.estimated_delivery', []);
        $migrator->add('hub_setting.payment_options', []);
        $migrator->add('hub_setting.order_fees', []);
        $migrator->add('hub_setting.taxes', []);
        $migrator->add('hub_setting.branding_options', []);
    }
}
