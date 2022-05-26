<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\ElasticDispensaryJob;
use App\Models\Repositories\Hub\ProductInventoryRepository;

class DispensaryInventoryAccessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $inventoryAccess, $oldInventoryAccess, $dispensaryId;
    protected $piRepository, $settingsService;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(bool $inventoryAccess, bool $oldInventoryAccess, int $dispensaryId)
    {
        $this->inventoryAccess = $inventoryAccess;
        $this->oldInventoryAccess = $oldInventoryAccess;
        $this->dispensaryId = $dispensaryId;
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
        if ($this->inventoryAccess !== $this->oldInventoryAccess) {

            if (tenant('id') !== $this->dispensaryId) {
                $this->settingsService->assignTenancy($this->dispensaryId);
            }

            $this->piRepository->inventoryAccess($this->inventoryAccess);
            dispatch(new ElasticDispensaryJob(tenant(), ['Product','Reward']));
        }
    }
}
