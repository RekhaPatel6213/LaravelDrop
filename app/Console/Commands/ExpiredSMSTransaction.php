<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExpiredSMSTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired:sms:transaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expired SMS Transaction as per expiry_date';

    protected $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->service = app('SMS.Service');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->service->expiredSMSTransaction();
    }
}
