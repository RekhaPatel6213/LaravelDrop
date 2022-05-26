<?php

namespace App\Http\Traits;

use App\Exceptions\OrderExitException;

trait OrderTrait
{
	public function getAllPendingOrders()
    {
        $count = 0;
        return $count > 0 ? false : true;
    }

    public function checkPendingOrders($message1, $message2)
    {
        $count = $this->getAllPendingOrders();
        if (!$count) {
            throw new OrderExitException(__('message.orderExitError', ['name' => __('message.'.$message1).__('product.'.strtolower($message2))]));
        }
    }
}