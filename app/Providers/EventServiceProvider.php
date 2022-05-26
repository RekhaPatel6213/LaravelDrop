<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\Admin\AdminUserCreated;
use App\Listeners\Admin\AdminUserCreatedListener;
use App\Events\Admin\ResetPassword;
use App\Listeners\Admin\ResetPasswordListener;
use Laravel\Cashier\Events\WebhookReceived;
use App\Listeners\Admin\Dispensary\StripeEventListener;
use App\Events\Admin\Dispensary\UserResetPassword;
use App\Listeners\Admin\Dispensary\UserResetPasswordListener;
use App\Events\Hub\InventoryLogEvent;
use App\Listeners\Hub\InventoryLogListener;
use App\Events\Hub\ProductInventoryLogEvent;
use App\Listeners\Hub\ProductInventoryLogListener;
use App\Events\Hub\InventoryEvent;
use App\Listeners\Hub\InventoryListener;
use App\Events\Hub\DispensaryUserEvent;
use App\Listeners\Hub\DispensaryUserListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        AdminUserCreated::class => [
            AdminUserCreatedListener::class,
        ],

        ResetPassword::class => [
            ResetPasswordListener::class,
        ],

        WebhookReceived::class => [
            StripeEventListener::class,
        ],

        UserResetPassword::class => [
            UserResetPasswordListener::class,
        ],

        ProductInventoryLogEvent::class => [
            ProductInventoryLogListener::class,
        ],

        InventoryLogEvent::class => [
            InventoryLogListener::class,
        ],

        InventoryEvent::class => [
            InventoryListener::class,
        ],

        DispensaryUserEvent::class => [
            DispensaryUserListener::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Subscribers\Admin\Dispensary\DomainSubscriber',
        'App\Subscribers\Admin\Dispensary\DispensaryUserSubscriber',
        'App\Subscribers\Admin\Dispensary\DispensaryPaymentMethodSubscriber',
        'App\Subscribers\Admin\Dispensary\DispensaryHourSetSubscriber',
        'App\Subscribers\Admin\Dispensary\DispensaryTimingSubscriber',
        'App\Subscribers\Admin\Dispensary\DispensarySubscriber',
        'App\Subscribers\Admin\Dispensary\LoyaltyProgramSubscriber',
        'App\Subscribers\Admin\Dispensary\MediaSubscriber',
        'App\Subscribers\Admin\Dispensary\ActivityLogSubscriber',
        'App\Subscribers\Admin\Dispensary\EmailTemplateSubscriber',
        'App\Subscribers\Admin\Dispensary\DropOffOptionSubscriber',
        'App\Subscribers\Admin\Dispensary\DispensaryCategorySubscriber',
        'App\Subscribers\Admin\Dispensary\SettingSubscriber',
        'App\Subscribers\Admin\Customer\DispensaryCustomerSubscriber',
        'App\Subscribers\Hub\ProductSubscriber',
        'App\Subscribers\Hub\ProductVariantSubscriber',
        'App\Subscribers\Hub\CategorySubscriber',
        'App\Subscribers\Hub\BrandSubscriber',
        'App\Subscribers\Admin\Dispensary\LegalSubscriber',
    ];


    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
