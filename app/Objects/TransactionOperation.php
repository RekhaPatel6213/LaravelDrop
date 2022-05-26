<?php

namespace App\Objects;

use Bavix\Wallet\Objects\Operation;
use Carbon\Carbon;

class TransactionOperation extends Operation
{
    protected $expiry_date;

    public function getExpiryDate(): Carbon
    {
        return $this->expiry_date;
    }

    public function setExpiryDate(): Carbon
    {
        return $this->expiry_date;
    }

    public function toArray(): array
    {
        return \array_merge(parent::toArray(), [
            'used_amount' => $this->meta['used_amount'] ?? 0,
            'expiry_date' => $this->meta['expiry_date'] ?? null,
        ]);
    }
}
