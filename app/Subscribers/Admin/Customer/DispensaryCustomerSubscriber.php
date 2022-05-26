<?php

namespace App\Subscribers\Admin\Customer;

use App\Events\Admin\Customer\CustomerCreated;
use App\Events\Admin\Customer\CustomerUpdated;
use App\Models\Repositories\Admin\Customer\DispensaryCustomerRepository;

class DispensaryCustomerSubscriber
{
    protected $repository;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(DispensaryCustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle the event.
     *
     * @param  CustomerCreated  $event
     * @return void
     */
    public function handleCustomerCreated(CustomerCreated $event)
    {
        $customer = $event->customer;
        $args = $event->args;
        $args['customer_id'] = $customer->id;
        $this->repository->create($args);
    }

    /**
     * Handle the event.
     *
     * @param  CustomerUpdated  $event
     * @return void
     */
    public function handleCustomerUpdated(CustomerUpdated $event)
    {
        $customer = $event->customer;
        $args = $event->args;

        if (!empty($args)) {
            $args ['customer_id'] = $customer->id;
            if (null !== ($dispCustomer = $this->repository->getDispensaryCustomer($customer->id))) {
                $this->repository->update($args, $dispCustomer->id);
            } else {
                $this->repository->create($args);
            }
        }
    }


    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Admin\Customer\CustomerCreated',
            'App\Subscribers\Admin\Customer\DispensaryCustomerSubscriber@handleCustomerCreated'
        );
        $events->listen(
            'App\Events\Admin\Customer\CustomerUpdated',
            'App\Subscribers\Admin\Customer\DispensaryCustomerSubscriber@handleCustomerUpdated'
        );
    }
}
