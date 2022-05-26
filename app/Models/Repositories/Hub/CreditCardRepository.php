<?php

namespace App\Models\Repositories\Hub;

use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Hub\CreditCardContract;
use App\Models\Hub\CreditCard;

/**
 * Class CreditCardRepository.
 *
 * @package namespace App\Models\Repositories\Hub;
 */
class CreditCardRepository extends BaseRepository implements CreditCardContract
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CreditCard::class;
    }

    public function list(?string $search, string $sortOn, string $sortOrder)
    {
       return $this->getQueryBuilder($this->model, $search, $sortOn, $sortOrder)->get();
    }

    public function storeCreditCard($creditCard, array $requestData){
        $data = [
            'strip_card' => $creditCard->id,
            'name' => $requestData['name'],
            'email' => $requestData['email'],
            'card_number' => $creditCard->last4,
            'brand' => $creditCard->brand,
            'exp_month' => $creditCard->exp_month,
            'exp_year' => $creditCard->exp_year,
            'funding' => $creditCard->funding,
            'country' => $creditCard->country,
            'is_default' => $this->model::YES
        ];
        $this->create($data);
    }
    
}
