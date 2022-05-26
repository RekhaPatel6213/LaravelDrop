<?php

namespace App\Services\Hub;

use App\Models\Admin\Dispensary\Subscription;
use App\Models\Admin\Dispensary\SubscriptionPrice;
use App\Models\Hub\CreditCard;

class CreditCardService
{
    protected $repository;
    protected $invoiceRepository;

    public function __construct($creditCardRepository, $invoiceRepository)
    {
        $this->repository = $creditCardRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    public function getSubscription(){
        $data= [];
        $allSubscriptions = Subscription::with('subscriptionPrice')->without('items')
                            ->where('dispensary_id', tenant('id'))
                            ->whereHas('items')
                            ->whereIn('name',[SubscriptionPrice::SUBSCRIPTION, SubscriptionPrice::SMS])
                            ->where('stripe_status',Subscription::ACTIVE)
                            ->orderBy('id','desc')
                            ->get();

        $subscription = $this->formateSubscription($allSubscriptions, SubscriptionPrice::SUBSCRIPTION);
        $smsSubscription = $this->formateSubscription($allSubscriptions, SubscriptionPrice::SMS);
        $data = ['subscription' => $subscription, 'sms' => $smsSubscription];
        return $data;
    }

    public function formateSubscription($subscriptions, string $type)
    {
        $subscription = $subscriptions->where('name', $type)->first();
        $subscriptionPrice =  $subscription->subscriptionPrice ?? null;
        return [
            'name' => $subscriptionPrice->name ?? null,
            'price' => $subscriptionPrice ? ($subscriptionPrice->amount/100) : 0,
            'currency' => SubscriptionPrice::STRIPE_CURRENCY,
            'status' => $subscription->stripe_status ?? null,
            'ends_at' => $subscription->ends_at ?? null,
            'sms' => $subscriptionPrice->sms ?? 0
        ];
    }

    public function invoiceList(array $requestData)
    {
        $search = $requestData['search'] ?? null;
        $sortOn = $requestData['sortOn'] ?? 'id';
        $sortOrder = $requestData['sort'] ?? 'desc';
        return $this->invoiceRepository->list($search, $sortOn, $sortOrder);
    }

    public function list(array $requestData)
    {
        $search = $requestData['search'] ?? null;
        $sortOn = $requestData['sortOn'] ?? 'is_default';
        $sortOrder = $requestData['sort'] ?? 'asc';
        return $this->repository->list($search, $sortOn, $sortOrder);
    }

    public function creditCardAdd(array $requestData)
    {
        $stripeToken = $requestData['stripe_token'];
        $dispensary = tenant();
        if (empty($dispensary->stripe_id)) { //Creating Stripe customer
            $dispensary->createAsStripeCustomer($stripeToken);
        }

        //Store Card Details in Stripe
        $creditCard = \Stripe\Customer::createSource($dispensary->stripe_id, ['source' => $stripeToken]);

        //Set Default Card in Stripe
        $this->setDefaultCard($creditCard->id);

        $this->repository->storeCreditCard($creditCard, $requestData);
        return ['message' => __('message.createSuccess', ['name' => __('dispensary.creditCard')])];
    }

    public function creditCardDefault(int $creditCardId)
    {
        return tenant()->createSetupIntent();
        $creditCard = $this->repository->find($creditCardId);
        $this->setDefaultCard($creditCard->strip_card, $creditCard);
        return ['message' => __('message.updateSuccess', ['name' => __('dispensary.creditCard')])];
    }

    public function creditCardDelete(int $creditCardId)
    {
        $dispensary = tenant();
        $creditCard = $this->repository->find($creditCardId);

        //Delete Card Details in Stripe
        $dispensary->findPaymentMethod($creditCard->strip_card)->delete();

        $this->repository->delete($creditCardId);

        if($creditCard->is_default === $this->repository->model()::YES){
            $newCreditCard = $this->repository->first();
            if($newCreditCard){
                $this->setDefaultCard($newCreditCard->strip_card, $newCreditCard);
            }
        }
        return ['message' => __('message.deleteSuccess', ['name' => __('dispensary.creditCard')])];
    }

    private function setDefaultCard(string $stripCard, CreditCard $creditCard = null)
    {
        $dispensary = tenant();
        $dispensary->updateDefaultPaymentMethod($stripCard);
        $this->repository->model()::where('is_default', $this->repository->model()::YES)->update(['is_default' => $this->repository->model()::NO]);

        if($creditCard){
            $creditCard->is_default = $this->repository->model()::YES;
            $creditCard->save(); 
        }
    }
}