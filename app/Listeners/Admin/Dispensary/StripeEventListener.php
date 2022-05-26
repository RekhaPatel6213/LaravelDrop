<?php

namespace App\Listeners\Admin\Dispensary;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Cashier\Events\WebhookReceived;

class StripeEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $subscriptionPriceService;

    public function __construct()
    {
       $this->subscriptionPriceService = app('subscriptionPriceService');
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(WebhookReceived $event)
    {
        $payload = $event->payload;
        $subscription = $payload['data']['object'];

        if(is_object($subscription))
            $subscription = $subscription->toArray();

        $subscriptionId = $subscription['id'];
        $stripeInvoiceId = $subscription['latest_invoice'];
        $dispensaryStripId = $subscription['customer'];

        switch($payload['type']) {
            case "customer.subscription.created":
                $this->subscriptionPriceService->stripeInvoiceByStripeCustomer($dispensaryStripId, $stripeInvoiceId);
                break;
            case "customer.subscription.updated":
                $this->subscriptionPriceService->addDispensarySubscriptionSMS($subscriptionId, $stripeInvoiceId);
                break;
            case "invoice.payment_succeeded":
                $stripeInvoiceId = $subscription['id'];
                $this->subscriptionPriceService->storeStripeInvoice($dispensaryStripId, $stripeInvoiceId);
                break;
        }
    }
}
