<?php

namespace App\Services\Admin;

use App\Http\Traits\OrderTrait;
use App\Jobs\DispensaryInventoryAccessJob;
use App\Jobs\HubSettingJob;
use App\Models\Admin\SettingsProperty as Setting;
use App\Models\Repositories\Hub\ProductInventoryRepository;
use App\Settings\DispensaryAccessSettings;
use App\Settings\DispensarySettings;
use App\Settings\WeedmapsSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Tenancy;

class SettingsService
{
    use OrderTrait;

    protected $piRepository;

    public function __construct()
    {
        $this->piRepository = new ProductInventoryRepository();
    }

    public function saveWeedmapsSettings(WeedmapsSettings $apiSettings, $requestData): WeedmapsSettings
    {
        $apiSettings->wm_client_id = $requestData['wm_client_id'];
        $apiSettings->wm_client_secret = $requestData['wm_client_secret'];
        $apiSettings->save();

        return $apiSettings;
    }

    public function saveDispensaryAccess(DispensaryAccessSettings $access, $requestData)
    {
        if (array_key_exists('inventory_feature', $requestData)) {
            if (!$this->getAllPendingOrders()) {
                return ['success' => false, 'errorMessage' => __('message.orderExitError', ['name' => __('message.enable_disable').__('product.inventoryFeature')])];
            }
            dispatch(new DispensaryInventoryAccessJob($requestData['inventory_feature'], $access->inventory_feature, tenant('id')));
        }

        $access->standalone = $requestData['standalone'] ?? $access->standalone;
        $access->smart_deals = $requestData['smart_deals'] ?? $access->smart_deals;
        $access->scheduled_delivery = $requestData['scheduled_delivery'] ?? $access->scheduled_delivery;
        $access->iframe_code = $requestData['iframe_code'] ?? $access->iframe_code;
        $access->seo_location = $requestData['seo_location'] ?? $access->seo_location;
        $access->driver_optimization = $requestData['driver_optimization'] ?? $access->driver_optimization;
        $access->inventory_feature = $requestData['inventory_feature'] ?? $access->inventory_feature;
        $access->save();

        return $access->toArray();
    }

    public function getHubSetting(DispensarySettings $setting, string $type = null)
    {
        $setting = $setting->toArray();

        return $setting[$type] ?? $setting;
    }

    public function saveHubSetting(DispensarySettings $setting, string $type, array $requestData)
    {
        $this->insertIfNotAdded('hub_setting', $type, tenant('id'));

        if (in_array($type, [Setting::DROPKIT])) {
            if (!$this->getAllPendingOrders()) {
                return ['errorMessage' => __('message.orderExitError', ['name' => __('message.enable_disable').__('product.dropkit')])];
            }
            $requestData = $requestData[$type];
        }

        $setting->$type = $requestData;
        $setting->save();

        dispatch(new HubSettingJob($setting, $type, tenant('id')));

        return $setting->$type;
    }

    public function assignTenancy(int $dispensaryId)
    {
        // Manual initialization of Tenancy
        $tenant = tenancy()->find($dispensaryId);
        if ($tenant) {
            tenancy()->initialize($tenant);
        }
    }

    public function insertIfNotAdded($group, $type, $dispensaryId)
    {
        $row = DB::table('settings')->select('id')
                ->where('dispensary_id', $dispensaryId)
                ->where('group', $group)
                ->where('name', $type)->first();
        if ($row === null) {
            $currentTimestamp = Carbon::now()->toDateTimeString();
            DB::table('settings')->insert(
                [
                   'group' => $group,
                   'dispensary_id' => $dispensaryId,
                   'name' => $type,
                   'locked' => 0,
                   'payload' => '[]',
                   'created_at' => $currentTimestamp,
               ]
            );
        }
    }
}
