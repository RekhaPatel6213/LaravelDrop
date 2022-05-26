<?php

namespace App\Models\Admin\Dispensary;

use App\Http\Traits\DispensaryTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * DispensaryPaymentMethod class is used for Hub.
 * This class will used for customer payment options setting in hub.
 * Class DispensaryPaymentMethod
 * @mixin Eloquent
 *
 *@OA\Schema(
 *   schema="PaymentMethodsListRes",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PaymentMethodsList")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 * )
 *
 * @OA\Schema(
 *   schema="PaymentMethodsListSingle",
 *   required={"data"},
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/PaymentMethodsList"),
 * )
 *
 * @OA\Schema(schema="PaymentMethodsList",
 *      @OA\Property(property="id", type="integer", description="Payment method id", example="5"),
 *      @OA\Property(property="dispensary_id", type="integer", description="dispensary id", example="1"),
 *      @OA\Property(property="payment_slug", type="string", description="payment slug", example="cod"),
 *      @OA\Property(property="payment_title", type="string", description="Payment method title", example="Cash On Delivery"),
 *      @OA\Property(property="description", type="string", description="Payment method description", example="PayPal Payment Method"),
 *      @OA\Property(property="status", type="string", description="Payment method status", example="ACTIVE"),
 *      @OA\Property(property="enable_tip", type="string", description="Enable Tip", example="NO"),
 *      @OA\Property(property="enable_cash", type="string", description="Enable cash", example="YES")
 *  )
 *
 * @OA\Schema(schema="PaymentMethods",
 *      @OA\Property(property="payment_title", type="string", description="Payment method title", example="PayPal"),
 *      @OA\Property(property="description", type="string", description="Payment method description", example="PayPal Payment Method"),
 *      @OA\Property(property="status", type="string", description="Payment method status", example="ACTIVE"),
 *      @OA\Property(property="enable_tip", type="string", description="Enable Tip", example="NO")
 *  )
 *
 * @OA\Schema(schema="PaymentMethodsUpdate",
 *      @OA\Property(
 *     property="payment_methods",
 *     type="array",
 *     example={
 *     {"id":50,"payment_title":"PayPal", "description":"PayPal Payment Method", "status":"ACTIVE", "enable_tip":"NO", "enable_cash":"NO"},
 *     {"id":51,"payment_title":"GooglePay", "description":"GooglePay Payment Method", "status":"ACTIVE", "enable_tip":"NO", "enable_cash":"NO"}
 *     },
 *      @OA\Items(
*                      @OA\Property(
*                         property="id",
*                         type="number",
*                         example=""
*                      ),
*                      @OA\Property(
*                         property="payment_title",
*                         type="string",
*                         example=""
*                      ),@OA\Property(
*                         property="description",
*                         type="string",
*                         example=""
*                      ),@OA\Property(
*                         property="status",
*                         type="string",
*                         example=""
*                      ),@OA\Property(
*                         property="enable_tip",
*                         type="string",
*                         example=""
*                      ),@OA\Property(
*                         property="enable_cash",
*                         type="string",
*                         example=""
*                      ),
 *     ),
 *  ),
 * )
 *
 *
 */


class DispensaryPaymentMethod extends Model implements Transformable
{
    use DispensaryTrait, TransformableTrait, HasSlug, SoftDeletes;

    public const PAYMENT_METHODS = [
        'cod' => [
            'slug' => 'cod',
            'title' => 'Cash On Delivery',
            'default_status' => 'ACTIVE',
        ],
        'card' => [
            'slug' => 'card',
            'title' => 'Card On Delivery',
            'default_status' => 'ACTIVE',
        ],

    ];

    const ACTIVE = 'ACTIVE';
    const INACTIVE = 'INACTIVE';

    protected $fillable = [
        'dispensary_id',
        'payment_slug',
        'payment_title',
        'enable_tip',
        'enable_cash',
        'status',
        ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('payment_title')
            ->saveSlugsTo('payment_slug')
            ->doNotGenerateSlugsOnUpdate();
    }
}
