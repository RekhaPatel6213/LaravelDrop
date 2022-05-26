<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Settings\DispensarySettings;
use App\Models\Admin\SettingsProperty as Setting;
use App\Models\Hub\ProductInventory;
use App\Models\Repositories\Hub\ProductInventoryRepository;

class HubSettingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $setting;
    protected $type;
    protected $dispensaryId;
    protected $inventoryFeature;
    protected $piRepository, $settingsService;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(DispensarySettings $setting, string $type, int $dispensaryId)
    {
        $this->setting = $setting;
        $this->type = $type;
        $this->dispensaryId = $dispensaryId;
        $this->inventoryFeature = app(\App\Settings\DispensaryAccessSettings::class)->inventory_feature;
        $this->piRepository = new ProductInventoryRepository;
        $this->settingsService = app('settingsService');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->type === Setting::DROPKIT) {

            if (tenant('id') !== $this->dispensaryId) {
                $this->settingsService->assignTenancy($this->dispensaryId);
            }

            $modelType = $this->inventoryFeature ? ProductInventory::TERRITORY : ( $this->setting->dropkit ? ProductInventory::TERRITORY : ProductInventory::DRIVER);
            $this->piRepository->allocateInventories($this->inventoryFeature, $modelType, null, null);
        }
    }
}
