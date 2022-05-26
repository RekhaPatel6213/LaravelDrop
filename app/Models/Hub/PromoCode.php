<?php

namespace App\Models\Hub;

use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 *  @OA\Schema(
 *   schema="PromoCodeList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PromoCodeInputData")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 *  )
 *
 * @OA\Schema(
 *   schema="PromoCodeInputDataRes",
 *   required={"data"},
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/PromoCodeInputAll"),
 *  )
 *
 *  @OA\Schema(
 *   schema="PromoCodeInputAll",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/PromoCodeInputData"),
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp")
 *   }
 *  )
 *
 * @OA\Schema(
 *   schema="PromoCode",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp")
 *   }
 *  )
 *
 *  @OA\Schema(schema="PromoCodeSortsOn", type="array",
 *     @OA\Items(type="string", enum={"discount_value"})
 * )
 *
 *
 * @OA\Schema(schema="PromoCodeInputData",
 *      @OA\Property(property="promo_code", type="string", description="Promo Code", example="Save 10 Percent"),
 *      @OA\Property(property="discount_type", type="string", description="Discount type", example="PERCENTAGE"),
 *      @OA\Property(property="discount_value", type="integer", description="Discount value", example="10"),
 *      @OA\Property(property="applicable_to", type="string", description="Promo code applicable location", example="1,2"),
 *      @OA\Property(property="applies_to", type="string", description="Applies to", example="ORDER"),
 *      @OA\Property(property="product_id", type="integer", description="Product Id", example="10"),
 *      @OA\Property(property="use_minimum", type="string", description="Minimum Requirement", example="NONE"),
 *      @OA\Property(property="minimum_amount", type="integer", description="Minimum purchase amount", example="100"),
 *      @OA\Property(property="unlimited", type="boolean", description="Unlimited usage", example="true"),
 *      @OA\Property(property="usage_limit", type="integer", description="Usage limit", example="1"),
 *      @OA\Property(property="start_date_time", type="string", description="Start date and Time", example="2022-01-07 00:00:00"),
 *      @OA\Property(property="end_date_time", type="string", description="Start date and Time", example="2022-01-08 00:00:00"),
 *  )
 *
 * @OA\Schema(schema="PromoCodeListData",
 *      @OA\Property(property="id", type="integer", description="Promo Code id", example="1"),
 *      @OA\Property(property="promo_overview", type="string", description="Promo overview", example="Get 10% off of entire order"),
 *      @OA\Property(property="status", type="string", description="status", example="ACTIVE"),
 *      @OA\Property(property="used_count", type="integer", description="Promo code used count", example="0"),
 *      @OA\Property(property="added_by", type="integer", description="Promo code added by", example="1"),
 *  )
 *
 *  @OA\Schema(schema="PromoCodePatchData",
 *      @OA\Property(property="status", type="string", description="status", example="INACTIVE")
 *   )
 *
 * @OA\RequestBody(
 *     request="PromoCodeRequest",
 *     description="PromoCode Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/PromoCodeInputData")
 *  )
 *
 */

/**
 * Class PromoCode.
 *
 * @package namespace App\Models\Hub;
 */
class PromoCode extends Model implements Transformable
{
    use TransformableTrait, DispensaryTrait, SoftDeletes;

    /**
     * SEARCH_FIELDS
     * is used for columns on which search can be applicable
     */
    const SEARCH_FIELDS = ['promo_code', 'promo_overview'];

    /**
     * DEFAULT_LIST_STATUS
     * listings having this status will be displayed by default in listing
     */
    const DEFAULT_LIST_STATUS = 'ACTIVE';

    /**
     * DEFAULT_LIST_ORDER
     * Default listing order of listing
     */
    const DEFAULT_LIST_ORDER = 'desc';

    const PERCENTAGE = 'PERCENTAGE';
    const FIXED = 'FIXED';
    const PRODUCT = 'PRODUCT';
    const ORDER = 'ORDER';
    const NONE = 'NONE';
    const AMOUNT = 'AMOUNT';
    const YES = 'YES';
    const NO = 'NO';
    const ACTIVE = 'ACTIVE';
    const INACTIVE = 'INACTIVE';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dispensary_id',
        'applicable_to',
        'promo_code',
        'promo_overview',
        'discount_type',
        'discount_value',
        'product_id',
        'applies_to',
        'use_minimum',
        'minimum_amount',
        'unlimited',
        'usage_limit',
        'start_date_time',
        'end_date_time',
        'status',
        'used_count',
        'added_by',
    ];

}
