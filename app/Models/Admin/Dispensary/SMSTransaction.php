<?php

namespace App\Models\Admin\Dispensary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSTransaction extends Model
{
    use HasFactory;

    protected $table = 'sms_transactions';

    protected $fillable = [
        'dispensary_id',
        'month',
        'type',
        'amount',
        'meta'
    ];

    protected $casts = [
        'meta' => 'json',
    ];
}
