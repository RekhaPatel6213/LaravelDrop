<?php

namespace App\Models\Territory;

use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Models\Hub\ModelInventory;
use App\Models\Hub\Inventory;

/**
 *  @OA\Schema(
 *   schema="TerritoriesList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TerritoryInputData")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta"),
 *  )
 *
 *  @OA\Schema(
 *   schema="AjaxTerritoriesList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AjaxTerritoryInputData")),
 *  )
 *
 *  @OA\Schema(
 *   schema="TerritoryInputDataRes",
 *   required={"data"},
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/TerritoryInputData"),
 *  )
 *
 *  @OA\Schema(
 *   schema="TerritoryTimeStamp",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp")
 *   }
 *  )
 *
 *  @OA\Schema(schema="TerritorySortsOn", type="array",
 *     @OA\Items(type="string", enum={"id", "name"})
 * )
 *
 *
 * @OA\Schema(schema="TerritoryInputData",
 *      @OA\Property(property="name", type="string", description="Territory name", example="Riverside"),
 *      @OA\Property(property="type", type="string", description="Territory Type", example="ZIPCODE", enum={"ZIPCODE", "GEO"}),
 *      @OA\Property(property="driver_ids", type="array",  @OA\Items(type="integer", example="5")),
 *      @OA\Property(property="dispensary_user_ids", type="array",  @OA\Items(type="integer", example="2"))
 *  )
 * 
 * @OA\Schema(schema="GeoTerritoryInputData",
 *      @OA\Property(property="minimum_order_cost", type="integer", description="Territory minimum order cost", example="20"),
 *      @OA\Property(property="delivery_fee", type="integer", description="Territory delivery fee", example="10"),
 *      @OA\Property(property="cost_for_free_delivery", type="integer", description="Territory cost_for free delivery", example="100")
 *  )
 * 
 * 
 * @OA\RequestBody(
 *     request="TerritoryData",
 *     description="Add New Territory Data Request body",
 *     required=true,
 *     @OA\JsonContent(
 *          oneOf={
 *              @OA\Schema(ref="#/components/schemas/ZipcodeTerritory"),
 *              @OA\Schema(ref="#/components/schemas/GeoTerritory"),
 *          },
 *          examples={
 *              @OA\Examples(example="ZipcodeTerritory", summary="Zipcode Territory",
 *                  value={
 *                      "name": "Riverside",
 *                      "type": "ZIPCODE",
 *                      "location_ids" : {1,2},
 *                      "inventory_id" : 1,
 *                      "driver_ids" : {1,2},
 *                      "dispensary_user_ids" : {1,2}
 *                  }
 *              ),
 *              @OA\Examples(example="GeoTerritory", summary="Geo Territory",
 *                  value={
 *                      "name": "Geo Riverside",
 *                      "type": "GEO",
 *                      "geo_points": {{{39.503511566981, -120.30875883106},{39.469595725616, -124.46159086231},{36.509084952266, -124.17594633106},{36.791141429226, -120.08903226856}},{{42.227680846264, -116.61197059771},{39.87515244938, -118.54556434771},{38.029895851563, -116.43618934771},{40.479522146678, -114.50259559771}}},
 *                      "minimum_order_cost" : 20,
 *                      "delivery_fee" : 10,
 *                      "cost_for_free_delivery" : 100,
 *                      "inventory_id" : 1,
 *                      "driver_ids" : {1,2},
 *                      "dispensary_user_ids" : {1,2}
 *                  }
 *              ), 
 *          }
 *      )
 * )
 * 
 * 
 * @OA\Schema(schema="ZipcodeTerritory", type="object",
 *  allOf={
 *      @OA\Schema(ref="#/components/schemas/TerritoryInputData"),
 *      @OA\Schema(@OA\Property(property="location_ids", type="array",  @OA\Items(type="integer", example="1")))
 *  }
 * )
 * 
 * @OA\Schema(schema="GeoTerritory", type="object",
 *  allOf={
 *      @OA\Schema(ref="#/components/schemas/TerritoryInputData"),
 *      @OA\Schema(ref="#/components/schemas/GeoTerritoryInputData"),
 *  }
 * )
 *
 * @OA\Schema(schema="AjaxTerritoryInputData",
 *      @OA\Property(property="id", type="integer", description="Territory Id", example="1"),
 *      @OA\Property(property="name", type="string", description="Territory name", example="Riverside"),
 *  )
 *
 *
 * @OA\RequestBody(
 *     request="TerritoryRequest",
 *     description="Territory Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/TerritoryInputData")
 *  )
 *
 */

/**
 * Class Territory.
 *
 * @package namespace App\Models\Territory;
 */
class Territory extends Model implements Transformable
{
    use TransformableTrait, DispensaryTrait, SoftDeletes;

    /*
     * DEFAULT_LIST_ORDER
     * Default listing order of Territories
     * */
    const DEFAULT_LIST_ORDER = 'desc';

    /*
     * SEARCH_FIELDS
     * is used for columns on which search can be applicable
     * */
    const SEARCH_FIELDS = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    const ZIPCODE = 'ZIPCODE';
    const GEO = 'GEO';

    protected $fillable = [
        'dispensary_id',
        'name',
        'type',
        'hour_set_id',
        'phone',
    ];

    public function territoryModule()
    {
        return $this->hasMany(TerritoryModule::class, 'territory_id', 'id');
    }

    public function inventoryModules()
    {
        return $this->morphToMany(Inventory::class, 'model', 'model_inventories');
    }

    public function geoPoints()
    {
        return $this->hasMany(TerritoryGeoPoint::class, 'territory_id', 'id');
    }

    public function taxesCosts()
    {
        return $this->hasOne(TaxesCostsSetting::class, 'territory_id', 'id')->whereNull('location_id');
    }
}
