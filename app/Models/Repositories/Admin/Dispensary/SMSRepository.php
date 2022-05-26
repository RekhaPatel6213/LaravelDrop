<?php

namespace App\Models\Repositories\Admin\Dispensary;

use App\Models\Admin\Dispensary\SubscriptionPrice;
use Carbon\Carbon;
use App\Models\Admin\Dispensary\SMSTransaction;

class SMSRepository
{
    public function getSMSPrices(array $requestData)
    {
        return  SubscriptionPrice::where('sms_group', $requestData['smsGroupName'])
                                ->where('status', SubscriptionPrice::ACTIVE)
                                ->where('type', SubscriptionPrice::SMS)
                                ->orderBy('sms', 'asc')
                                ->get();
    }

    public function getSubscriptionPrice(string $stripPlanId)
    {
        return SubscriptionPrice::where('stripe_price_id', $stripPlanId)->first();
    }

    public function addSMSTransaction(int $dispensaryId, int $smsCount)
    {
        $deductMessage = __('subscription.deductSmsDescription', ['smsCount' =>$smsCount, 'dispensary' => $dispensaryId, 'date' => Carbon::now()]);

        $withdrawData = [
            'dispensary_id' => $dispensaryId,
            'month' => date('Ym'),
            'amount' => $smsCount,
            'meta' => $deductMessage,
        ];

        SMSTransaction::create($withdrawData);
    }

    public function getTotalSMSPerMonth(int $dispensaryId, string $month)
    {
        return SMSTransaction::where([
            ['dispensary_id', '=', $dispensaryId ],
            ['month', '=', $month ]
        ])->sum('amount');
    }

    public function deleteSMSTransactionPerMonth(int $dispensaryId, string $month)
    {
        SMSTransaction::where([
            ['dispensary_id', '=', $dispensaryId ],
            ['month', '=', $month ]
        ])->delete();
    }
}
