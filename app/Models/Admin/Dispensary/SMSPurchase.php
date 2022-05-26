<?php

namespace App\Models\Admin\Dispensary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *      schema="SmsHistory",
 *      required={"data"},
 *          @OA\Property(property="data", type="array", @OA\Items(
 *          @OA\Property(property="total_SMS",type="integer",format="int32",description="Total SMS", example="1000")
 *      ))
 * )
 *
 * @OA\RequestBody(
 *     request="PruchaseSMS",
 *     description="Add sms in dispensary Request body",
 *     required=true,
 *     @OA\JsonContent(
 *      oneOf={
 *          @OA\Schema(ref="#/components/schemas/WithGiftSMS"),
 *          @OA\Schema(ref="#/components/schemas/WithPurchaseSMS"),
 *          @OA\Schema(ref="#/components/schemas/CanceledSMSSubscription"),
 *      },
 *      examples={
 *          @OA\Examples(example="WithGiftSMS", summary="With Gift SMS",
 *          value={
 *              "dispensary_id":1,
 *              "sms": 1000
 *          }),
 *          @OA\Examples(example="WithPurchaseSMS", summary="With Purchase SMS",
 *          value={
 *              "dispensary_id": 1,
 *              "stripe_price_id": "price_1Jux45SJCzzs25Bn3ecREzuq"
 *          }),
 *          @OA\Examples(example="CanceledSMSSubscription", summary="Canceled SMS Subscription",
 *          value={
 *              "dispensary_id": 1,
 *              "sms_schedule": "canceled"
 *          })
 *      }
 *    )
 * )
 *
 * @OA\Schema(schema="WithPurchaseSMS", type="object",
 *      @OA\Property(property="dispensary_id", type="integer", description="Dispensary Id"),
 *      @OA\Property(property="stripe_price_id", type="string", description="Stripe Price Id"),
 * )
 *
 * @OA\Schema(schema="WithGiftSMS", type="object",
 *      @OA\Property(property="dispensary_id", type="integer", description="Dispensary Id",example=1),
 *      @OA\Property(property="sms", type="integer", description="Address Id", example=1),
 * )
 *
 * @OA\Schema(schema="CanceledSMSSubscription", type="object",
 *      @OA\Property(property="dispensary_id", type="integer", description="Dispensary Id",example=1),
 *      @OA\Property(property="sms_schedule", type="string", description="Canceled SMS Subscription", example="canceled"),
 * )
 *
 * @OA\Schema(
 *   schema="SmsGroupList",
 *   @OA\Property(property="name", type="string", description="Group Name", example="Gift SMS"),
 *      @OA\Property(property="type", type="string", description="Group Type", example="GIFT"),
 *      @OA\Property(property="months", type="integer", format="int32", description="Months", example="1"),
 * )
 *
 * @OA\Schema(
 *   schema="SmsPriceList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/SmsPriceListData"))
 * )
 *
 * @OA\Schema(
 *   schema="SmsPriceListData",
 *      @OA\Property(property="name", type="string", description="Strip Price name", example="Subscription Price Name"),
 *      @OA\Property(property="stripe_price_id", type="string", description="Stripe Price Id", example="price_1Jux45SJCzzs25Bn3ecREzuq"),
 *      @OA\Property(property="amount", type="integer", format="int32", description="Subscription Price Amount", example=1000),
 *      @OA\Property(property="interval", type="string", description="Interval Type", example="DAY"),
 *      @OA\Property(property="sms", type="integer", format="int32", description="SMS", example="1000"),
 *      @OA\Property(property="recurring_type", type="float", description="Subscription Plan Recurring Type", example="recurring"),
 * )
 *
 * @OA\RequestBody(
 *     request="UsedSMS",
 *     description="using dispensary sms request body",
 *     required=true,
 *     @OA\JsonContent(
 *          @OA\Property(property="dispensary_id", type="integer", format="int32", description="Dispensary Id",example=1),
 *          @OA\Property(property="used_sms", type="integer", format="int32", description="Address Id", example=5),
 *     )
 *  )
 */

class SMSPurchase extends Model
{
    use HasFactory;

    public const ACTIVE = 'ACTIVE',
        ENDED = 'ENDED',
        CANCELED = 'CANCELED',
        FREE = 'FREE',
        GIFT='GIFT',
        ONETIME = 'ONETIME',
        RECURRING = 'RECURRING';

    public const SMS_SCHEDULE = ['GIFT_SMS', 'ONE_TIME_PURCHASE', '3_MONTHS_RECURRING', '6_MONTHS_RECURRING', '12_MONTHS_RECURRING'];

    protected $fillable = [
        'dispensary_id',
        'type',
        'plan_id',
        'sms'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'admin_id'
    ];
}
