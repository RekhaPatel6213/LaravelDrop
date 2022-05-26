<?php

namespace App\Models\Repositories\Contracts\Hub;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CreditCardContract.
 *
 * @package namespace App\Models\Repositories\Contracts\Hub;
 */
interface CreditCardContract extends RepositoryInterface
{
   public function list(?string $search, string $sortOn, string $sortOrder);

   public function storeCreditCard(array $creditCard, array $requestData);
}
