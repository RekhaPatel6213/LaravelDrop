<?php

namespace App\Objects;

use Spatie\Activitylog\ActivityLogger as BaseActivityLogger;

class ActivityLogger extends BaseActivityLogger
{
    public function bulkTransferId(int $bulkTransferId = null)
    {
        return $this->setBulkTransferId($bulkTransferId);
    }

    public function setBulkTransferId(int $bulkTransferId = null)
    {
        $this->activity->bulk_transfer_id = $bulkTransferId;
        return $this;
    } 
}
