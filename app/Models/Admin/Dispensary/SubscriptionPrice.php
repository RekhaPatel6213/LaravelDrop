<?php

namespace App\Models\Admin\Dispensary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   schema="SubscriptionPriceList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/SubscriptionPrice")),
 * )
 * @OA\Schema(schema="SubscriptionPrice",
 *      @OA\Property(property="stripe_price_id", type="string", description="Stripe Price Id", example="price_1JuwznSJCzzs25Bnve5i2k16"),
 *      @OA\Property(property="stripe_product_id", type="string", description="Stripe Product Id", example="prod_KVw1SBHVDz0fPl"),
 *      @OA\Property(property="amount", type="integer", format="int32", description="Amount", example="1000"),
 *      @OA\Property(property="interval", type="string", description="Interval", example="month"),
 *      @OA\Property(property="trial_days",type="integer", format="int32", description="Trial Period Days", example="30"),
 *      @OA\Property(property="sms", type="integer", format="int32", description="SMS Count", example="100"),
 * )
 *
 * @OA\Schema(schema="SubscriptionPriceData",
 *      @OA\Property(property="name", type="string", description="Strip Price Name", example="Subscription Price"),
 *      @OA\Property(property="amount", type="integer", format="int32", description="Amount", example="1000"),
 *      @OA\Property(property="type", type="string", description="type", example="SUBSCRIPTION", enum={"SUBSCRIPTION", "SMS"}),
 *      @OA\Property(property="recurring", type="integer", description="recurring or not", example="YES", enum={"YES", "NO"}),
 *      @OA\Property(property="interval", type="string", description="Interval Type", example="DAY", enum={"DAY", "WEEK", "MONTH", "YEAR"}),
 *      @OA\Property(property="months", type="integer", format="int32",  description="Interval Months", example="1"),
 *      @OA\Property(property="sms", type="integer", format="int32",  description="SMS Count", example="100")
 * )
 *
 * @OA\Schema(schema="StripeBalanceAdd",
 *      @OA\Property(property="dispensary_id", type="integer", format="int32", description="Dispensary Id", example="1"),
 *      @OA\Property(property="amount", type="integer", format="int32", description="Amount", example="1000"),
 *      @OA\Property(property="title",type="string", description="Title", example="Add Payment for manual pay"),
 *      @OA\Property(property="description",type="string", description="Description", example="Add Payment for manual pay")
 * )
 *
 * @OA\Schema(
 *   schema="StripeInvoiceList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/StripeInvoice")),
 * )
 * @OA\Schema(schema="StripeInvoice",
 *      @OA\Property(property="id", type="string", description="Stripe Invoice Id"),
 *      @OA\Property(property="currency", type="string", description="Currency"),
 *      @OA\Property(property="status", type="string", description="Status"),
 *      @OA\Property(property="hosted_invoice_url", type="string", description="Hosted Invoice Url"),
 *      @OA\Property(property="invoice_pdf", type="string", description="Invoice Pdf Url"),
 *      @OA\Property(property="period_start", type="string", description="Period Start"),
 *      @OA\Property(property="period_end",type="string", description="Period End"),
 *      @OA\Property(property="subscription", type="string", description="Subscription Id"),
 * )
 *
 * @OA\Schema(schema="StripeInvoiceDetail",
 *      @OA\Property(property="dispensary_id", type="integer", format="int32", description="Dispensary Id", example="1"),
 *      @OA\Property(property="stripe_invoice_id", type="string", description="Stripe Invoice Id", example="in_1JuF7tSJCzzs25BnIxq0D4pf")
 * )
 *
 * @OA\Schema(
 *   schema="StripeBalanceTransactionList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(
 *      @OA\Property(property="id", type="string", description="Stripe Balance Transaction Id", example="cbtxn_1JwO7cSJCzzs25BnGFcBCF3e"),
 *      @OA\Property(property="currency", type="string", description="Stripe Currency", example="usd"),
 *      @OA\Property(property="amount", type="integer", description="Transaction Amount", example="-10"),
 *      @OA\Property(property="metadata", type="object", description="Stripe Balance Metadata")
 *   )),
 * )
 */


class SubscriptionPrice extends Model
{
    use HasFactory;

    public const STRIPE_CURRENCY = 'usd';
    public const YES = 'YES',
                SMS = 'SMS',
                SUBSCRIPTION = 'SUBSCRIPTION',
                GIFT='GIFT',
                ONETIME = 'one_time',
                RECURRING = 'recurring',
                ACTIVE = 'ACTIVE',
                INACTIVE = 'INACTIVE';

    protected $fillable = [
        'name',
        'stripe_price_id',
        'stripe_product_id',
        'amount',
        'interval',
        'interval_count',
        'trial_days',
        'sms',
        'sms_group',
        'type',
        'recurring_type',
        'status'
    ];
}
