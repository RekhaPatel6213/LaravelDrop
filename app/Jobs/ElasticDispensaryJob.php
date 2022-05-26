<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Admin\Dispensary\Dispensary;

class ElasticDispensaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dispensary;
    protected $actions;
    protected $isDeleted;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Dispensary $dispensary, array $actions = null, bool $isDeleted = false)
    {
        $this->dispensary = $dispensary;
        $this->actions = $actions;
        $this->isDeleted = $isDeleted;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dispensary = $this->dispensary;
    }
}
