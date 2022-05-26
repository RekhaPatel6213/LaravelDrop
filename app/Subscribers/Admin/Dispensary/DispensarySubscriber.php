<?php

namespace App\Subscribers\Admin\Dispensary;

use App\Events\Admin\Dispensary\DispensaryCreated;
use App\Events\Admin\Dispensary\DispensaryUpdated;
use App\Models\Admin\Dispensary\Dispensary;
use App\Models\Admin\Dispensary\SubscriptionPrice;
use Illuminate\Support\Carbon;

class DispensarySubscriber
{
    private $service;

    public function __construct()
    {
        $this->service = app('subscriptionPriceService');
    }

    /**
     * Handle the event.
     *
     * @param  DispensaryCreated  $event
     * @return void
     */
    public function handleDispensaryCreated(DispensaryCreated $event)
    {
        $dispensary = $event->dispensary;

        //Creating Stripe customer
        $dispensary->createAsStripeCustomer();

        $this->service->crateStripSubscription($dispensary->id, $dispensary->subscription_type);
    }

    /**
     * Handle the event.
     *
     * @param  DispensaryUpdated  $event
     * @return void
     */
    public function handleDispensaryUpdated(DispensaryUpdated $event)
    {
        $dispensary = $event->dispensaryUpdatedObj;

        if (empty($dispensary->stripe_id)) {
            //Creating Stripe customer
            $dispensary->createAsStripeCustomer();

            $this->service->crateStripSubscription($dispensary->id, $dispensary->subscription_type);
        } elseif ($dispensary->stripe_id) {
            $dispensary->syncStripeCustomerDetails();

            if (!$dispensary->subscribed(SubscriptionPrice::SUBSCRIPTION)) {
                $this->service->crateStripSubscription($dispensary->id, $dispensary->subscription_type);
            }
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Admin\Dispensary\DispensaryCreated',
            'App\Subscribers\Admin\Dispensary\DispensarySubscriber@handleDispensaryCreated'
        );

        $events->listen(
            'App\Events\Admin\Dispensary\DispensaryUpdated',
            'App\Subscribers\Admin\Dispensary\DispensarySubscriber@handleDispensaryUpdated'
        );
    }
}
