<?php

namespace App\Services\Admin\Dispensary;

use App\Models\Admin\Dispensary\SubscriptionPrice;
use App\Models\Admin\Dispensary\Dispensary;
use App\Models\Admin\Dispensary\Invoice;
use App\Models\Repositories\Admin\Dispensary\SMSRepository;
use Laravel\Cashier\Cashier;
use \Stripe\Stripe;
use Illuminate\Support\Arr;
use Carbon\Carbon;

use Laravel\Cashier\Subscription as CashierSubscription;

class SubscriptionPriceService
{
    protected $smsRepository;

    public function __construct()
    {
        $this->smsRepository = new SMSRepository();
    }

    public function list()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $prices =  \Stripe\Price::all(['limit' => 50]);
        $priceArray = [];

        $priceCount = SubscriptionPrice::count();

        foreach ($prices->data as $price) {
            if ($priceCount === 0) {
                $priceArray[] = $this->formatePriceData($price);
            } elseif (!$this->checkStripPriceExistsByPriceId($price->id)) {
                $priceArray[] = $this->formatePriceData($price);
            }
        }

        if (count($priceArray) > 0) {
            SubscriptionPrice::insert($priceArray);
        }

        return SubscriptionPrice::where('status', SubscriptionPrice::ACTIVE)->get();
    }

    private function formatePriceData($priceData)
    {
        $metadata = $priceData['metadata'];
        $smsCount = isset($metadata['smsCount']) ? $metadata['smsCount'] : 0;
        $subscriptionType = isset($metadata['subscriptionType']) ? $metadata['subscriptionType'] : null;
        $months = isset($metadata['months']) ? (int)$metadata['months'] : '';

        return [
            'name' => $priceData['nickname'],
            'stripe_price_id' => $priceData['id'],
            'stripe_product_id' => $priceData['product'],
            'amount' => $priceData['unit_amount'],
            'interval' => $priceData['type'] === 'recurring' ? $priceData['recurring']['interval'] : null,
            'interval_count' => $priceData['type'] === 'recurring' ? $priceData['recurring']['interval_count'] : null,
            'trial_days' => $priceData['type'] === 'recurring' ? $priceData['recurring']['trial_period_days'] : null,
            'sms' => $smsCount,
            'sms_group' => $months >= 0 ? $this->getSMSGroup($months) : null,
            'type' => $subscriptionType ?? SubscriptionPrice::SUBSCRIPTION,
            'recurring_type' => $priceData['type']
        ];
    }

    private function getSMSGroup($months)
    {
        $groups = config('admin_setting.sms_groups');
        $groupData = Arr::first($groups, function ($value, $key) use ($months) {
            return $value['months'] === $months;
        });
        return $groupData ?  $groupData['name'] : null;
    }

    private function formateStripePriceData($priceRequestData)
    {
        $priceName = $priceRequestData['name'];

        $priceData = [
            'nickname' => $priceName,
            'unit_amount' => $priceRequestData['amount'],
            'currency' => SubscriptionPrice::STRIPE_CURRENCY,
            'product_data' => ['name' => $priceName],
        ];

        $metadata = [
            'smsCount' => $priceRequestData['sms'],
            'subscriptionType' => $priceRequestData['type']
        ];

        if ($priceRequestData['recurring'] === SubscriptionPrice::YES) {
            $priceData['recurring'] = [
                'interval' => strtolower($priceRequestData['interval'])
            ];

            $metadata['months'] = $priceRequestData['months'];
        }

        $priceData['metadata'] = $metadata;

        return $priceData;
    }

    public function create(array $requestData)
    {
        $priceData = $this->formateStripePriceData($requestData);

        if (!$this->checkStripPriceExistsByName($priceData['nickname'])) {
            Stripe::setApiKey(config('services.stripe.secret'));
            $price = \Stripe\Price::create($priceData);

            $price = $this->formatePriceData($price);
            SubscriptionPrice::create($price);
            return $price;
        } else {
            return ['message'=> __('subscription.stripePriceExist')];
        }
    }

    private function checkStripPriceExistsByPriceId(string $priceId)
    {
        $price = SubscriptionPrice::where('stripe_price_id', $priceId)->first();
        if (empty($price)) {
            return false;
        }
        return true;
    }

    private function checkStripPriceExistsByName(string $name)
    {
        $price = SubscriptionPrice::where('name', $name)->first();
        if (empty($price)) {
            return false;
        }
        return true;
    }

    public function stripeBalanceAdd(array $requestData)
    {
        $dispensary = Dispensary::find($requestData['dispensary_id']);

        //If not exist stripe customer id then creating new stripe customer
        if (empty($dispensary->stripe_id)) {
            $dispensary->createAsStripeCustomer();
        }

        $balanceNote = 'Customer Name:'. $dispensary->name. '#Customer Id:'. $dispensary->id .'#IP Address:' .request()->ip();
        $metadata['metadata'] =[
            'customer_id' => $dispensary->id,
            'customer_name'=> $dispensary->name,
            'title' => $requestData['title'] ?? '',
            'description' => $requestData['description'] ?? '',
            'ip_address' => request()->ip(),
        ];

        //Add Amount balance in stripe customer account
        $dispensary->applyBalance(-(int) $requestData['amount'], $balanceNote, $metadata);

        return ['message' => __('subscription.stripeBalanceAdd', ['dispensary' => $dispensary->name])];
    }


    public function invoiceList(int $dispensaryId)
    {
        return Invoice::where('dispensary_id', $dispensaryId)->get();
    }

    public function invoiceDetail(int $invoiceId)
    {
        return Invoice::find($invoiceId);
    }

    public function stripeInvoiceList(int $dispensaryId)
    {
        $dispensary = Dispensary::find($dispensaryId);
        $invoices = $dispensary->invoices()->toArray();

        if($invoices){
            foreach($invoices as $invoice){
                $this->storeStripeInvoice($invoice, $dispensaryId);
            }
        }

        return $invoices;
    }

    public function stripeInvoiceDetail(array $requestData)
    {
        $dispensary = Dispensary::find($requestData['dispensary_id']);
        return $dispensary->findInvoice($requestData['stripe_invoice_id'])->toArray();
    }

    public function stripeInvoiceByStripeCustomer(string $dispensaryStripId, string $stripeInvoiceId)
    {
        $dispensary = Dispensary::where('stripe_id', $dispensaryStripId)->first();
        $invoice = $dispensary->findInvoice($stripeInvoiceId)->toArray();
        $this->storeStripeInvoice($invoice, $dispensary->id);
    }

    public function storeStripeInvoice(array $invoice, int $dispensaryId = null)
    {
        $invoiceData = [
            'dispensary_id' => $dispensaryId != null ? $dispensaryId : (isset($invoice['lines']['data'][0]['metadata']['dispensary_id']) ? $invoice['lines']['data'][0]['metadata']['dispensary_id'] : null),
            'stripe_invoice_id' => $invoice['id'],
            'stripe_price_id'=> $invoice['lines']['data'][0]['price']['id'],
            'stripe_subscription_id' => $invoice['subscription'],
            'invoice_pdf' => $invoice['invoice_pdf'],
            'status' => $invoice['status'],
            'amount' => ($invoice['total']/100),
            'description' => isset($invoice['lines']['data'][0]['description']) ? $invoice['lines']['data'][0]['description'] : null,
            'invoice_date' => date('Y-m-d', $invoice['created'])
        ];

        Invoice::updateOrCreate(
            ['stripe_invoice_id' => $invoice['id']],
            $invoiceData
        );
    }

    public function addDispensarySubscriptionSMS(string $subscriptionId, string $stripeInvoiceId)
    {
        $subscription = CashierSubscription::where('stripe_id', $subscriptionId)->first();
        
        $subscriptionPrice = $this->smsRepository->getSubscriptionPrice($subscription->stripe_price);

        $dispensaryId = $subscription->dispensary_id;

        $this->stripeBalanceAdd(['dispensary_id' => $dispensaryId, 'amount' => $subscriptionPrice->amount]);

        if ($subscriptionPrice->type === SubscriptionPrice::SUBSCRIPTION) {
            $this->smsService->addDispensaryFreeSMS((int)$subscriptionPrice->sms, $dispensaryId);
        }
    }

    public function stripeCustomerBalanceTransaction(int $dispensaryId)
    {
        $dispensary = Dispensary::find($dispensaryId);
        $transactions = $dispensary->balanceTransactions();
        $transactionData = [];
        foreach ($transactions as $key => $transaction) {
            // Retrieve the related invoice when available...
            $transaction = $transaction->toArray();

            $transactionData[$key] = [
                'id' => $transaction['id'],
                'amount' => $transaction['amount'],
                'currency' => $transaction['currency'],
                'description' => $transaction['description'],
                'metadata' => $transaction['metadata']
            ];
        }

        return $transactionData;
    }

    public function crateStripSubscription(int $dispensaryId, string $stripeId, string $subscriptionType = SubscriptionPrice::SUBSCRIPTION)
    {
        $subscriptionPrice = $this->smsRepository->getSubscriptionPrice($stripeId);
        $default = $subscriptionType === SubscriptionPrice::SMS ? strtolower(SubscriptionPrice::SMS) : SubscriptionPrice::SUBSCRIPTION;
        $dispensary = Dispensary::find($dispensaryId);

        if ($subscriptionPrice && empty($dispensary->subscription($default))) {

            //Add Amount balance in stripe customer account
            $this->stripeBalanceAdd(['dispensary_id' => $dispensaryId, 'amount' => $subscriptionPrice->amount]);

            $default = $subscriptionType === SubscriptionPrice::SMS ? strtolower(SubscriptionPrice::SMS) : SubscriptionPrice::SUBSCRIPTION;
            $dispensary->newSubscription($default, [$subscriptionPrice->stripe_price_id])->create(); //'price_1KRe7HSJCzzs25BnocXXbqDc'

            if ($subscriptionType === SubscriptionPrice::SMS) {
                $dispensary->subscription($default)->cancelAt(
                    now()->addDays($subscriptionPrice->interval_count)
                );
            } else {
                //Add Subscriber Free SMS to Dispensary
                $this->smsService->addDispensaryFreeSMS((int)$subscriptionPrice->sms, $dispensaryId);
            }
            return ['message' => __('subscription.smsPurchaseSuccess')];
        } else {
            return ['message' => __('subscription.subscriptionExist', ['name' => $default])];
        }
    }

    public function purchaseOneTimeSMS(int $dispensaryId, string $stripeId, int $invoiceAmount)
    {
        $dispensary = Dispensary::find($dispensaryId);
        
        //If not exist stripe customer id then creating new stripe customer
        if (empty($dispensary->stripe_id)) {
            $dispensary->createAsStripeCustomer();
        }

        //Add Amount balance in stripe customer account
        $this->stripeBalanceAdd(['dispensary_id' => $dispensaryId, 'amount' => $invoiceAmount]);

        $metadata['metadata'] = ['dispensary_id' => $dispensaryId, 'ip_address' => request()->ip()];
        $dispensary->invoicePrice($stripeId, 1, $metadata);
        return ['message' => __('subscription.smsPurchaseSuccess')];
    }

    public function cancelStripeSubscription(int $dispensaryId, string $subscriptionType = SubscriptionPrice::SUBSCRIPTION)
    {
        $dispensary = Dispensary::find($dispensaryId);
        $default = $subscriptionType === SubscriptionPrice::SMS ? strtolower(SubscriptionPrice::SMS) : SubscriptionPrice::SUBSCRIPTION;
        $dispensary->subscription($default)->cancel();
        return ['message' => __('subscription.subscriptionCanceledSuccess')];
    }
}
