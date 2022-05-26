<?php

namespace App\Models\Hub;

use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 *
 * @OA\Schema(
 *   schema="DealList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/DealsListingData")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 * )
 *
 * @OA\Schema(
 *   schema="DealsInputDataResp",
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/DealsInputDataRespSub")
 *  )
 *
 * @OA\Schema(
 *   schema="DealsInputDataRespSub",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/DealsInputData"),
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp")
 *   }
 *  )
 *
 * @OA\Schema(
 *   schema="BrandInputDataResp",
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BrandInputData"))
 *  )
 *
 *  @OA\Schema(
 *   schema="Deal",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp")
 *   }
 *  )
 *
 *  @OA\Schema(schema="DealSortsOn", type="array",
 *     @OA\Items(type="string", enum={"id", "name"})
 * )
 * @OA\Schema(schema="DealPatchData",
 *      @OA\Property(property="status", type="string", description="status", example="INACTIVE")
 * )
 *
 *
 *@OA\RequestBody(
 *     request="DealAddRequest",
 *     required=true,
 *     @OA\JsonContent(
 *      oneOf={
 *          @OA\Schema(ref="#/components/schemas/CartTypeData"),
 *          @OA\Schema(ref="#/components/schemas/CategoryTypeData"),
 *          @OA\Schema(ref="#/components/schemas/ProductTypeData"),
 *          @OA\Schema(ref="#/components/schemas/BrandTypeData"),
 *          @OA\Schema(ref="#/components/schemas/SpendXTypeDataCart"),
 *          @OA\Schema(ref="#/components/schemas/SpendXTypeDataProduct"),
 *          @OA\Schema(ref="#/components/schemas/BuyXTypeDataProduct"),
 *      },
 *      examples={
 *          @OA\Examples(example="CartTypeData", summary="Deal Type : Cart",
 *          value={
 *              "name" : "Get 10% off of your order",
 *              "description" : "Get 10% off of your order",
 *              "discount_type" : "PERCENT",
 *              "deal_type" : "NORMAL",
 *              "applied_on" : "CART",
 *              "discount_value" : "10.00",
 *              "total_usage_limit" : "10",
 *              "per_user_limit" : "1",
 *              "min_spend" : "0",
 *              "max_spend" : "0",
 *              "active_days" : {0,1,2},
 *              "start_date" : "2022-01-17",
 *              "start_time" : "12:00",
 *              "end_time" : "21:00",
 *              "exclude_products" : {5},
 *              "exclude_categories" : {1,2},
 *              "exclude_brands" : {1,2},
 *
 *          }),
 *
 *     @OA\Examples(example="ProductTypeData",summary="Deal Type : Product",
 *          value={
 *              "name" : "Get 50% off of select Flower > Sativa products",
 *              "description" : "Get 50% off of select Flower > Sativa products",
 *              "discount_type" : "PERCENT",
 *              "deal_type" : "NORMAL",
 *              "applied_on" : "PRODUCT",
 *              "discount_value" : "10.00",
 *              "total_usage_limit" : "10",
 *              "per_user_limit" : "1",
 *              "min_spend" : "0",
 *              "max_spend" : "0",
 *              "active_days" : {0,1,2},
 *              "start_date" : "2022-01-17",
 *              "start_time" : "12:00",
 *              "end_time" : "21:00",
 *              "applied_products" : {3,4},
 *              "exclude_products" : {5},
 *              "exclude_categories" : {1,2},
 *              "exclude_brands" : {1,2},
 *
 *          }),
 *
 *     @OA\Examples(example="BrandTypeData",summary="Deal Type : Brand",
 *          value={
 *              "name" : "Get 10% off of all Flower products",
 *              "description" : "Get 10% off of all Flower products",
 *              "discount_type" : "PERCENT",
 *              "deal_type" : "NORMAL",
 *              "applied_on" : "BRAND",
 *              "discount_value" : "10.00",
 *              "total_usage_limit" : "10",
 *              "per_user_limit" : "1",
 *              "min_spend" : "0",
 *              "max_spend" : "0",
 *              "active_days" : {0,1,2},
 *              "start_date" : "2022-01-17",
 *              "start_time" : "12:00",
 *              "end_time" : "21:00",
 *              "applied_brands" : {1,2},
 *              "exclude_products" : {5},
 *              "exclude_categories" : {1,2},
 *              "exclude_brands" : {1,2},
 *          }),
 *
 *     @OA\Examples(example="CategoryTypeData",summary="Deal Type : Category",
 *          value={
 *              "name" : "Get 5% off of select Cartridges, Plant / Seed > Clone products",
 *              "description" : "Get 5% off of select Cartridges, Plant / Seed > Clone products",
 *              "discount_type" : "PERCENT",
 *              "deal_type" : "NORMAL",
 *              "applied_on" : "CATEGORY",
 *              "discount_value" : "5.00",
 *              "total_usage_limit" : "100",
 *              "per_user_limit" : "1",
 *              "min_spend" : "50",
 *              "max_spend" : "500",
 *              "active_days" : {0,1,2},
 *              "start_date" : "2022-01-17",
 *              "start_time" : "12:00",
 *              "end_time" : "21:00",
 *              "applied_categories" : {1,2},
 *              "exclude_products" : {5},
 *              "exclude_categories" : {1,2},
 *              "exclude_brands" : {1,2},
 *          }),
 *
 *     @OA\Examples(example="SpendXTypeDataCart",summary="Deal Type : Spend X Cart",
 *          value={
 *              "name" : "Spend $100, get 10% off of this deal",
 *              "description" : "Spend $100, get 10% off of this deal",
 *              "discount_type" : "PERCENT",
 *              "deal_type" : "SPEND-X",
 *              "applied_on" : "TOTAL",
 *              "condition_on" : "CART",
 *              "discount_value" : "10.00",
 *              "total_usage_limit" : "100",
 *              "per_user_limit" : "1",
 *              "min_spend" : "50",
 *              "max_spend" : "500",
 *              "active_days" : {0,1,2},
 *              "start_date" : "2022-01-17",
 *              "start_time" : "12:00",
 *              "end_time" : "21:00",
 *              "exclude_products" : {5},
 *              "exclude_categories" : {1,2},
 *              "exclude_brands" : {1,2},
 *          }),
 *
 *     @OA\Examples(example="SpendXTypeDataProduct",summary="Deal Type : Spend X Prod",
 *          value={
 *              "name" : "Spend $100 Baked Goods | MAC | MacSeed 101, Bulk Flower | MAC | MacSeed 102, get 10% off of Janu Pod | MAC | MacSeed 103",
 *              "description" : "Spend $100 Baked Goods | MAC | MacSeed 101, Bulk Flower | MAC | MacSeed 102, get 10% off of Janu Pod | MAC | MacSeed 103",
 *              "discount_type" : "PERCENT",
 *              "deal_type" : "SPEND-X",
 *              "applied_on" : "PRODUCT",
 *              "condition_on" : "PRODUCT",
 *              "discount_value" : "10.00",
 *              "total_usage_limit" : "100",
 *              "per_user_limit" : "1",
 *              "min_spend" : "50",
 *              "max_spend" : "500",
 *              "active_days" : {0,1,2},
 *              "start_date" : "2022-01-17",
 *              "start_time" : "12:00",
 *              "end_time" : "21:00",
 *              "condition_products" : {4},
 *              "applied_products" : {3},
 *              "exclude_products" : {5},
 *              "exclude_categories" : {1,2},
 *              "exclude_brands" : {1,2},
 *          }),
 *
 *     @OA\Examples(example="BuyXTypeDataProduct", summary="Deal Type : Buy X",
 *          value={
 *              "name" : "Buy 2 Baked Goods | MAC | MacSeed 101, Bulk Flower | MAC | MacSeed 102, get 5.00% off of Parks, MAC",
 *              "description" : "Buy 2 Baked Goods | MAC | MacSeed 101, Bulk Flower | MAC | MacSeed 102, get 5.00% off of Parks, MAC",
 *              "discount_type" : "PERCENT",
 *              "deal_type" : "BUY-X",
 *              "applied_on" : "BRAND",
 *              "condition_on" : "PRODUCT",
 *              "number_of_x" : 2,
 *              "discount_value" : "10.00",
 *              "total_usage_limit" : "100",
 *              "per_user_limit" : "1",
 *              "min_spend" : "50",
 *              "max_spend" : "500",
 *              "active_days" : {0,1,2},
 *              "start_date" : "2022-01-17",
 *              "start_time" : "12:00",
 *              "end_time" : "21:00",
 *              "condition_products" : {4},
 *              "applied_brands" : {3},
 *              "exclude_products" : {5},
 *              "exclude_categories" : {1,2},
 *              "exclude_brands" : {1,2},
 *          }),
 *      }
 *    )
 *  )
 *
 *
 *
 *
 * @OA\Schema(schema="CartTypeData"
 *  )
 *
 * @OA\Schema(schema="ProductTypeData"
 *  )
 *
 * @OA\Schema(schema="SpendXTypeDataCart"
 *  )
 *
 * @OA\Schema(schema="BrandTypeData"
 *  )
 *
 * @OA\Schema(schema="CategoryTypeData"
 *  )
 *
 * @OA\Schema(schema="SpendXTypeDataProduct"
 *  )
 *
 * @OA\Schema(schema="BuyXTypeDataProduct"
 *  )
 *
 * @OA\Schema(schema="DealsListingData",
 *      @OA\Property(property="id",type="integer",format="int32", description="Deal Id", example="1"),
 *      @OA\Property(property="name",type="string", description="Deal name", example="Spend $100 Baked Goods"),
 *      @OA\Property(property="discount_type",type="string", description="Discount type", example="PERCENT"),
 *      @OA\Property(property="frequency",type="string", description="Frequency", example="RECURRING"),
 *  )
 *
 * @OA\Schema(schema="DealsInputData",
 *      @OA\Property(property="id",type="integer",format="int32", description="Deal Id", example="1"),
 *      @OA\Property(property="name",type="string", description="Deal name", example="Get 10% off of your order"),
 *      @OA\Property(property="deal_type",type="string", description="Deal type", example="NORMAL"),
 *      @OA\Property(property="discount_type",type="string", description="Discount type", example="PERCENT"),
 *      @OA\Property(property="frequency",type="string", description="Frequency", example="RECURRING"),
 *      @OA\Property(property="slug",type="string", description="Slug", example="get-10-off-of-your-order"),
 *      @OA\Property(property="description",type="string", description="deal description", example="Get 10% off of your order"),
 *      @OA\Property(property="applied_on",type="string", description="deal applied on", example="CART"),
 *      @OA\Property(property="discount_value",type="integer", description="Deal discount value", example="10"),
 *      @OA\Property(property="total_usage_limit",type="integer", description="Deal usage limit", example="100"),
 *      @OA\Property(property="per_user_limit",type="integer", description="Deal per user limit", example="1"),
 *      @OA\Property(property="min_spend",type="integer", description="Deal min spend", example="50"),
 *      @OA\Property(property="max_spend",type="integer", description="Deal max spend", example="500"),
 *      @OA\Property(property="active_days",type="string", description="Deal active days", example="1,2,3"),
 *      @OA\Property(property="start_time",type="date", description="Deal start time", example="12:00"),
 *      @OA\Property(property="end_time",type="date", description="Deal end time", example="06:00"),
 *      @OA\Property(property="start_date",type="date", description="Deal start date", example="2022-01-17"),
 *      @OA\Property(property="end_date",type="date", description="Deal end date", example="2022-01-19"),
 *      @OA\Property(property="added_by",type="integer", description="Deal added by", example="1"),
 *      @OA\Property(property="status",type="integer", description="Deal status", example="ACTIVE"),
 *  )
 *
 * @OA\Schema(schema="BrandInputData",
 *      @OA\Property(property="id",type="integer",format="int32", description="Brand Id", example="1"),
 *      @OA\Property(property="name",type="string", description="Brand name", example="BRAND 1")
 *  )
 *
 */

/**
 * Class Deal.
 *
 * @package namespace App\Models\Hub;
 */
class Deal extends Model implements Transformable
{
    use TransformableTrait, DispensaryTrait, HasSlug;

    /*
     * SEARCH_FIELDS
     * is used for columns on which search can be applicable
     * */
    const SEARCH_FIELDS = ['name', 'id'];

    /*
     * DEFAULT_LIST_STATUS
     * listings having this status will be displayed by default in listing
     * */
    const DEFAULT_LIST_STATUS = 'ACTIVE';

    /*
     * DEFAULT_LIST_ORDER
     * Default listing order of listing
     * */
    const DEFAULT_LIST_ORDER = 'desc';


    const NORMAL = 'NORMAL';
    const BUY_X = 'BUY-X';
    const SPEND_X = 'SPEND-X';
    const FREE = 'FREE';
    const AMOUNT = 'AMOUNT';
    const FIXED = 'FIXED';
    const PERCENT = 'PERCENT';
    const CART = 'CART';
    const PRODUCT = 'PRODUCT';
    const CATEGORY = 'CATEGORY';
    const BRAND = 'BRAND';
    const TOTAL = 'TOTAL';
    const ACTIVE = 'ACTIVE';
    const INACTIVE = 'INACTIVE';
    const RECURRING = 'RECURRING';
    const LIMITED = 'LIMITED';
    const INCLUDE = 'include';
    const EXCLUDE = 'exclude';
    const APPLIED = 'applied';
    const CONDITIONAL = 'conditional';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dispensary_id',
        'name',
        'sku',
        'slug',
        'description',
        'deal_type',
        'discount_type',
        'applied_on',
        'condition_on',
        'discount_value',
        'total_usage_limit',
        'per_user_limit',
        'min_spend',
        'max_spend',
        'days',
        'start_date',
        'end_date',
        'number_of_x',
        'added_by',
        'status',
    ];


    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getFrequencyAttribute()
    {
        return $this->end_date == null ? self::RECURRING : self::LIMITED;
    }

}
