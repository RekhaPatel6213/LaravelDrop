<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToPrimaryModel;

class CustomerNotification extends Model
{
    use BelongsToPrimaryModel;

    protected $fillable = [
        'notification_id',
        'customer_id',
        'unread'
    ];

    public function getRelationshipToPrimaryModel(): string
    {
        return 'notification';
    }

    public function model()
    {
        return $this->morphTo();
    }

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }
}
