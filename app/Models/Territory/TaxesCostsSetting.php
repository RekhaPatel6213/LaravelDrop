<?php

namespace App\Models\Territory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 *  @OA\Schema(
 *   schema="TaxCostsList",
 *   required={"data"},
 *   @OA\Property(property="data", ref="#/components/schemas/TaxCostListData"),
 *  )
 *
 * @OA\Schema(
 *   schema="DelCostsList",
 *   required={"data"},
 *   @OA\Property(property="data", ref="#/components/schemas/DelCostListData"),
 *  )
 *
 * @OA\Schema(
 *   schema="TaxCostListData",
 *     allOf={
 *      @OA\Property(property="(int) {territory_id}", ref="#/components/schemas/LocationListElement"),
 *   }
 *  )
 *
 * @OA\Schema(
 *   schema="DelCostListData",
 *     allOf={
 *      @OA\Property(property="(int) {territory_id}", ref="#/components/schemas/DelLocationListElement"),
 *   }
 *  )
 *
 *
 *  @OA\Schema(
 *   schema="TaxCosts",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp")
 *   }
 *  )
 *
 *  @OA\Schema(schema="TaxSortsOn", type="array",
 *     @OA\Items(type="string", enum={"name", "city", "zip_code","state_tax", "local_tax", "excise_tax", "cannabis_tax_medical", "cannabis_tax_adult"})
 * )
 *
 * @OA\Schema(schema="DeliveryCostsSortsOn", type="array",
 *     @OA\Items(type="string", enum={"name", "city", "zip_code"})
 * )
 *
 * @OA\Schema(schema="LocationListElement",
 *      @OA\Property(property="territory_id", type="integer", description="Territory id", example="1"),
 *      @OA\Property(property="territory_name", type="string", description="Territory name", example="Riverside"),
 *      @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/LocationListElementSubTaxMerge")),
 * )
 *
 * @OA\Schema(schema="DelLocationListElement",
 *      @OA\Property(property="territory_id", type="integer", description="Territory id", example="1"),
 *      @OA\Property(property="territory_name", type="string", description="Territory name", example="Riverside"),
 *      @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/LocationListElementSubDelMerge")),
 * )
 *
 * @OA\Schema(
 *   schema="LocationListElementSubDelMerge",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/LocationListElementSubCommon"),
 *      @OA\Schema(ref="#/components/schemas/LocationListElementSubDel"),
 *   }
 *  )
 *
 * @OA\Schema(
 *   schema="LocationListElementSubTaxMerge",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/LocationListElementSubCommon"),
 *      @OA\Schema(ref="#/components/schemas/LocationListElementSubTax"),
 *   }
 *  )
 *
 *@OA\Schema(schema="LocationListElementSubCommon",
 *      @OA\Property(property="location_id", type="integer", description="location id", example="1"),
 *      @OA\Property(property="city", type="string", description="city name", example="Boston"),
 *      @OA\Property(property="zip_code", type="integer", description="zip code", example="85322"),
 * )
 *
 * @OA\Schema(schema="LocationListElementSubDel",
 *      @OA\Property(property="minimum_order_cost", type="integer", description="Minimum order cost", example="50"),
 *      @OA\Property(property="cost_for_free_delivery", type="integer", description="Cost for free delivery", example="80"),
 *      @OA\Property(property="delivery_fee", type="integer", description="Delivery fee", example="10"),
 * )
 *
 * @OA\Schema(schema="LocationListElementSubTax",
 *      @OA\Property(property="state_tax", type="integer", description="State tax", example="0.5"),
 *      @OA\Property(property="local_tax", type="integer", description="Local tax", example="0.5"),
 *      @OA\Property(property="excise_tax", type="integer", description="Excise tax", example="0.5"),
 *      @OA\Property(property="cannabis_tax_medical", type="integer", description="Cannabis tax (medical)", example="0.5"),
 *      @OA\Property(property="cannabis_tax_adult", type="integer", description="Cannabis tax (adult)", example="0.5"),
 *
 * )
 *
 * @OA\Schema(schema="TaxUpdateData",
 *      @OA\Property(property="territory_id", type="integer", description="Territory Id", example="1"),
 *      @OA\Property(property="location_id", type="integer", description="Location Id", example="10"),
 *      @OA\Property(property="state_tax", type="integer", description="State Tax", example="1"),
 *      @OA\Property(property="local_tax", type="integer", description="Local Tax", example="1"),
 *      @OA\Property(property="excise_tax", type="integer", description="Excise Tax", example="1"),
 *      @OA\Property(property="cannabis_tax_medical", type="integer", description="Cannabis Tax Medical", example="1"),
 *      @OA\Property(property="cannabis_tax_adult", type="integer", description="Cannabis Tax Adult", example="1"),
 * )
 *
 * @OA\Schema(schema="DeliveryCostsUpdateData",
 *      @OA\Property(property="territory_id", type="integer", description="Territory Id", example="1"),
 *      @OA\Property(property="location_id", type="integer", description="Location Id", example="10"),
 *      @OA\Property(property="minimum_order_cost", type="integer", description="Minimum Order Cost", example="10"),
 *      @OA\Property(property="delivery_fee", type="integer", description="Delivery Fee", example="5"),
 *      @OA\Property(property="cost_for_free_delivery", type="integer", description="Minimum For Free Delivery", example="20")
 * )
 *
 * Class TaxesCostsSetting.
 *
 * @package namespace App\Models\Admin\Dispensary;
 */

class TaxesCostsSetting extends Model implements Transformable
{
    use TransformableTrait;

    /*
         * DEFAULT_LIST_ORDER
         * Default listing order
         * */
    const DEFAULT_LIST_ORDER = 'desc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'territory_id',
        'location_id',
        'minimum_order_cost',
        'delivery_fee',
        'cost_for_free_delivery',
        'state_tax',
        'local_tax',
        'excise_tax',
        'cannabis_tax_medical',
        'cannabis_tax_adult',
    ];
}
