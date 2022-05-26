<?php

namespace App\Models\Admin\Dispensary;

use Bavix\Wallet\Models\Transaction as WalletTransaction;
use App\Http\Traits\DispensaryTrait;

class Transaction extends WalletTransaction
{
    use DispensaryTrait;
    /**
     * {@inheritdoc}
     */
    public function getFillable(): array
    {
        return array_merge($this->fillable, [
            'used_amount',
            'expiry_date',
            'dispensary_id'
        ]);
    }
}
