<?php

namespace App\Services\Admin\Dispensary;

use App\Models\Repositories\Admin\Dispensary\SMSRepository;
use App\Models\Admin\Dispensary\SubscriptionPrice;
use App\Models\Admin\Dispensary\Dispensary;
use App\Models\Admin\Dispensary\Transaction;
use DB;


use Laravel\Cashier\Subscription as CashierSubscription;
use Carbon\Carbon;

class SMSService
{
    protected $smsRepository;
    protected $service;

    public function __construct()
    {
        $this->smsRepository = new SMSRepository();
        $this->service = app('subscriptionPriceService');
    }

    public function getQueryBuilder(int $dispensaryId)
    {
        DB::enableQueryLog();
        $smsHistory = [];
        $allMonths = Transaction::selectRaw("year(created_at) year, month(created_at) month")
                                    ->where('payable_id', $dispensaryId)
                                    ->groupBy('year', 'month')
                                    ->orderBy('year', 'desc')
                                    ->orderBy('month', 'desc')
                                    ->get();
        if ($allMonths) {
            foreach ($allMonths as $month) {
                $date = $month->year.'-'.$month->month.'-28 00:00:00';

                $totalSMS = Transaction::selectRaw("sum(amount) as total_sms")
                                        ->where('payable_id', $dispensaryId)
                                        ->where('type', 'deposit')
                                        ->whereRaw('"'.$date.'" between `created_at` and `expiry_date`')
                                        ->first();
                $smsHistory[$month->month.'-'.$month->year]['total_SMS'] = $totalSMS;
                $smsHistory[$month->month.'-'.$month->year]['ending_SMS'] = $totalSMS;

                $smsHistory[$month->month.'-'.$month->year]['query'] = DB::getQueryLog();
            }
        }
        return $smsHistory;
    }

    public function getSMSGroups()
    {
        return config('admin_setting.sms_groups');
    }

    public function getSMSPrices(array $requestData)
    {
        return $this->smsRepository->getSmsPrices($requestData);
    }

    public function purchaseSMS(array $requestData)
    {
        $dispensaryId = $requestData['dispensary_id'];
        $smsCount = 0;
        $message = [];

        if (isset($requestData['stripe_price_id'])) {
            $subscriptionPrice = $this->smsRepository->getSubscriptionPrice($requestData['stripe_price_id']);
            $stripePriceId = $subscriptionPrice->stripe_price_id;
            $smsCount = $subscriptionPrice->sms;

            if ($subscriptionPrice && $subscriptionPrice->interval_count > 0) {
                $message = $this->service->crateStripSubscription($dispensaryId, $stripePriceId, SubscriptionPrice::SMS);
            } elseif ($subscriptionPrice && $subscriptionPrice->interval_count === null) {
                $message = $this->service->purchaseOneTimeSMS($dispensaryId, $stripePriceId, (int)$subscriptionPrice->amount);
            }
        }

        if (isset($requestData['sms_schedule']) && $requestData['sms_schedule'] === 'canceled') {
            $message = $this->service->cancelStripeSubscription($dispensaryId, SubscriptionPrice::SMS);
        }


        $smsCount = isset($requestData['sms']) ? $requestData['sms'] : $smsCount;

        //Add Sms in wallet
        $depositSms = $this->depositSMSWallet($dispensaryId, $smsCount);

        $message = !empty($message) ? $message : $depositSms;
        return $message;
    }

    public function depositSMSWallet(int $dispensaryId, $smsCount, Dispensary $dispensary = null)
    {
        if ($smsCount > 0) {
            if ($dispensary === null) {
                $dispensary = Dispensary::find($dispensaryId);
            }

            $currentDate = Carbon::now();
            $endDate = $currentDate->addDays(3);

            $dispensary->deposit($smsCount, ['expiry_date' => $endDate], true);
        }
        return $message = ['message' => __('subscription.smsPurchaseSuccess')];
    }

    public function addDispensaryFreeSMS(int $smsCount, int $dispensaryId)
    {
        $smsData = [
            'sms' => $smsCount,
            'dispensary_id' => $dispensaryId,
        ];
        $this->depositSmsWallet($smsData);
    }

    public function deductSMS(array $requestData)
    {
        $dispensaryId = $requestData['dispensary_id'];
        $smsCount = $requestData['used_sms'];
        $dispensary = Dispensary::with(['wallet'])->find($dispensaryId);

        if ($smsCount > 0 && $dispensary->balance && $dispensary->balance >= $smsCount) {
            $this->smsRepository->addSMSTransaction($dispensaryId, $smsCount);

            $transactions = $dispensary->wallet->transactions->where('type', 'deposit');
            if ($transactions) {
                $remainSms = $smsCount;
                foreach ($transactions as $transaction) {
                    if ($transaction->amount !== $transaction->used_amount && !$transaction->expiry_date->isPast() && $remainSms > 0) {
                        $remainUsedSms = $transaction->amount - $transaction->used_amount;

                        if ($remainUsedSms >= $remainSms) {
                            $used_amount = $remainSms;
                            $remainSms = 0;
                        } elseif ($remainUsedSms < $remainSms) {
                            $used_amount = $remainUsedSms;
                            $remainSms = $remainSms - $remainUsedSms;
                            $transaction->confirmed = 0;
                        }

                        $transaction->used_amount = $transaction->used_amount + $used_amount;
                        $transaction->meta = array_merge($transaction->meta, ['used' => 'used '.$used_amount.' sms from dispensary '.$dispensaryId.' on '.Carbon::now()]);
                        $transaction->save();
                    }
                }
            }
            return ['message' => __('subscription.smsDeductSuccess')];
        }
        return ['message' => __('subscription.smsNotSufficient')];
    }

    public function expiredSmsTransaction()
    {
        $transactionList = Transaction::where('confirmed', 1)->where('expiry_date', '<', Carbon::now())->where('type', 'deposit')->get();
        if ($transactionList) {
            foreach ($transactionList as $transaction) {
                $transaction->wallet->resetConfirm($transaction);
            }
        }
    }

    public function withdrawAllSmsPerMonth()
    {
        $month = date('Ym');
        $dispensaries = Dispensary::all();

        if ($dispensaries) {
            foreach ($dispensaries as $dispensary) {
                $dispensaryId = $dispensary->id;
                $smsCount = $this->smsRepository->getTotalSMSPerMonth($dispensaryId, $month);

                if ($smsCount > 0) {
                    $deductMessage = __('subscription.deductSmsDescription', ['smsCount' =>$smsCount, 'dispensary' => $dispensaryId, 'date' => Carbon::now()]);
                    $dispensary->withdraw($smsCount, ['description' => $deductMessage]);

                    $this->smsRepository->deleteSMSTransactionPerMonth($dispensaryId, $month);
                }
            }
        }
    }
}
