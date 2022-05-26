<?php

namespace App\Models\Admin\Dispensary;

use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{
    public const ACTIVE = 'active',
                 INACTIVE = 'inactive',
                 PAST_DUE ='past_due';

    public const SUBSCRIPTION = 'subscription';
    public const SMS = 'sms';


    public function subscriptionPrice()
    {
        return $this->hasOneThrough(
            SubscriptionPrice::class,
            SubscriptionItem::class,
            'subscription_id',
            'stripe_price_id',
            'id',
            'stripe_price'
        );
    }
}
