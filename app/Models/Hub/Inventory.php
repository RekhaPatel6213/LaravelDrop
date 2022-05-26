<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Traits\DispensaryTrait;
use App\Models\Territory\Territory;

/**
 * @OA\Schema(
 *   schema="InventoryList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Inventory")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 *  )
 * @OA\Schema(
 *   schema="Inventory",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/InventoryCommonData"),
 *      @OA\Schema(
 *          @OA\Property(property="id",type="integer",format="int32",description="ID", example="1"),
 *          @OA\Property(property="models", type="string", description="Inventory Model's Name", example="Model Name 1, Model Name 2"),
 *      ),
 *      @OA\Schema(ref="#/components/schemas/StandardTimestampWithoutDeleted"),
 *   }
 *  )
 *
 * @OA\Schema(
 *   schema="InventoryDetail",
 *      @OA\Property(property="data", type="object",
 *          allOf={
 *              @OA\Schema(ref="#/components/schemas/InventoryCommonData"),
 *              @OA\Schema(
 *                  @OA\Property(property="id",type="integer",format="int32",description="ID", example="1"),
 *                  @OA\Property(property="models", type="object", description="Inventory Model Details",
 *                      @OA\Property(property="data", type="array", @OA\Items(
 *                          @OA\Property(property="model_id",type="integer",format="int32",description="Model Id", example="1"),
 *                          @OA\Property(property="model_name", type="string", description="Model Name", example="Inventory Model Name")
 *                      ))
 *                  )
 *              ),
 *              @OA\Schema(ref="#/components/schemas/StandardTimestampWithoutDeleted"),
 *          }
 *      )
 *  )
 *
 * @OA\Schema(schema="InventoryCommonData",
 *     @OA\Property(property="name", type="string", description="Name", example="Inventory"),
 *     @OA\Property(property="is_sale", type="string", description="For Sale", example="YES"),
 *     @OA\Property(property="model_type", type="string", description="Model Type", example="Territory", enum={"Territory", "Driver"})
 *  )
 *
 * @OA\Schema(schema="InventoryInputData",
 *  allOf={
 *      @OA\Schema(ref="#/components/schemas/InventoryCommonData"),
 *      @OA\Schema(
 *          @OA\Property(property="model_ids", type="array",  @OA\Items(type="integer", example="1")),
 *      )
 *  }
 * )
 *
 * @OA\RequestBody(
 *     request="InventoryRequest",
 *     description="Inventory Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/InventoryInputData")
 *  )
 *
 * @OA\Schema(schema="InventorySortsOn", type="array",
 *     @OA\Items(type="string", enum={"name", "is_sale", "model_type", "model_ids"})
 * )
 */

/**
 * Class Inventories.
 *
 * @package namespace App\Models\Hub;
 */
class Inventory extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes, DispensaryTrait;
    public const DRIVER = 'Driver',
        DEFAULT_LIST_ORDER = 'desc';
    const SEARCH_FIELDS = [
        'inventories.name',
        'inventories.is_sale'
    ];
    const MODEL_TYPE = ['Territory' , 'Driver'];
    const IS_SALE = ['YES' , 'NO'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dispensary_id',
        'name',
        'metrc_location_id',
        'is_sale'
    ];

    /**
     * Get model inventory.
     */
    public function modelInventory() {
        return $this->hasMany(ModelInventory::class, 'inventory_id','id');
    }

    public function territoryModules()
    {
        return $this->morphToMany(Territory::class, 'module','territory_modules');
    }

    public static function boot() {
        parent::boot();
        static::deleting(function($inventory) { // before delete() method call this
            $inventory->modelInventory()->delete();
        });
    }

    public function scopeHasModel($query, $modelType)
    {
        return $query->whereHas('modelInventory', function($query) use($modelType) {
                    $query->where('model_type', $modelType);
                });
    }

    public function scopeWithModel($query, $modelType)
    {
        return $query->with('modelInventory', function($query) use($modelType) {
                    $query->where('model_type', $modelType);
                });
    }

    public function scopeInIds($query, $ids)
    {
        return $query->whereIn('id', $ids);
    }
}
