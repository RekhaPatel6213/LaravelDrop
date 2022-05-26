<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToPrimaryModel;
use App\Models\Territory\Territory;
use App\Models\Driver\DriverUser;
use App\Models\Hub\Inventory;
use Vanilo\Product\Models\ProductState;
use App\Models\Hub\Category;

/**
 * @OA\Schema(
 *  schema="ProductInventoryList",
 *  @OA\Property(property="data", type="object",
 *      allOf={
 *          @OA\Schema(
 *              @OA\Property(property="stock_details", type="object",
 *                  @OA\Property(property="product_detail_id", type="object", ref="#/components/schemas/StockDetails")),
 *          ),
 *          @OA\Schema(ref="#/components/schemas/InventoryDetailSingle"),
 *      }
 *  )
 * )
 *
 * @OA\Schema(
 *  schema="ProductInventorySingle",
 *  @OA\Property(property="data", type="object",
 *      allOf={
 *          @OA\Schema(
 *              @OA\Property(property="stock_details", type="array", @OA\Items(type="string", example=".5G, 1G"))
 *          ),
 *          @OA\Schema(ref="#/components/schemas/InventoryDetailSingle"),
 *      }
 *  )
 * )
 *
 * @OA\Schema(
 *  schema="StockDetails",
 *      allOf={
 *          @OA\Schema(
 *              @OA\Property(property="variant_id", type="integer" ,format="int32", description="Product Variant Id", example="1"),
 *              @OA\Property(property="allocated_stock", type="integer", description="Product Allocated Stock", example="10"),
 *              @OA\Property(property="unallocated_stock", type="integer", description="Product Unallocated Stock", example="90")
 *          ),
 *          @OA\Schema(ref="#/components/schemas/ProductVariant"),
 *          @OA\Schema(ref="#/components/schemas/Stock")
 *      }
 * )
 *
 * @OA\Schema(
 *  schema="allocatedStock",
 *  @OA\Property(property="inventory_model_name", type="object",
 *      allOf={
 *          @OA\Schema(ref="#/components/schemas/ModelType"),
 *          @OA\Schema(
 *              @OA\Property(property="index", type="object",
 *                  allOf={
 *                      @OA\Schema(ref="#/components/schemas/ProductVariant"),
 *                      @OA\Schema(ref="#/components/schemas/Stock")
 *                  }
 *              ),
 *              @OA\Property(property="model_id", type="integer", description="Reallocate Model Id", example="1"),
 *              @OA\Property(property="model_name", type="string", description="Inventory Model Name", example="Inventory Model Name")
 *          )
 *      }
 *  )
 * )
 *
 * @OA\Schema(
 *  schema="InventoryDetailSingle",
 *      allOf={
 *          @OA\Schema(
 *              @OA\Property(property="id",type="integer", format="int32", description="Product Id", example="1"),
 *              @OA\Property(property="quantity_type", type="string", description="Product Quantity Type", example="PRE-PACKAGED", enum={"PRE-PACKAGED", "GRAMS", "UNITS"}),
 *              @OA\Property(property="allocated_stock", type="object", ref="#/components/schemas/allocatedStock")
 *          ),
 *          @OA\Schema(ref="#/components/schemas/ModelType"),
 *          @OA\Schema(ref="#/components/schemas/ProductCommonInputDataOne")
 *      }
 * )
 *
 * @OA\Schema(
 *  schema="Stock",
 *  @OA\Property(property="stock", type="integer" ,format="int32", description="Product Stock", example="100")
 * )
 *
 * @OA\Schema(
 *  schema="ProductVariant",
 *  @OA\Property(property="variant", type="string", description="Product Variant Name", example="Product Variant Name")
 * )
 *
 * @OA\Schema(
 *  schema="ModelType",
 *  @OA\Property(property="model_type", type="string", description="Model Type", example="Territory", enum={"Territory", "Driver", "Inventory"})
 * )
 *
 * @OA\Schema(
 *  schema="ProductDetailId",
 *  @OA\Property(property="product_detail_id", type="integer", description="Product Detail Id", example="1")
 * )
 *
 * @OA\Schema(
 *  schema="ProductInventoryData",
 *  allOf={
 *      @OA\Schema(
 *          @OA\Property(property="model_ids", type="array",  @OA\Items(type="integer", example="1"))
 *      ),
 *      @OA\Schema(ref="#/components/schemas/ProductDetailId"),
 *      @OA\Schema(ref="#/components/schemas/Stock"),
 *      @OA\Schema(ref="#/components/schemas/ModelType")
 *  }
 * )
 *
 * @OA\Schema(
 *  schema="ReallocateInventoryData",
 *  allOf={
 *      @OA\Schema(
 *          @OA\Property(property="model_id", type="integer", description="Reallocate Model Id", example="1"),
 *          @OA\Property(property="is_unallocated", type="string", description="Unallocated", example="NO", enum={"YES", "NO"}),
 *      ),
 *      @OA\Schema(ref="#/components/schemas/Stock")
 *  }
 * )
 *
 * @OA\Schema(schema="ProductInventorySortsOn", type="array",
 *     @OA\Items(type="string", enum={"product_name", "model_name","added_by", "created_at"})
 * )
 *
 * @OA\Schema(
 *  schema="ProductStock",
 *  allOf={
 *      @OA\Schema(ref="#/components/schemas/ProductDetailId"),
 *      @OA\Schema(ref="#/components/schemas/Stock")
 *  }
 * )
 *
 * @OA\Schema(
 *  schema="InventoryLogList",
 *  @OA\Property(property="data", type="array", @OA\Items(
 *      allOf={
 *          @OA\Schema(
 *              @OA\Property(property="product_id", type="integer", description="Product Id", example="1"),
 *              @OA\Property(property="product_name", type="string", description="Product Name", example="Product Name"),
 *              @OA\Property(property="type", type="string", description="Inventory Type", example="Hub Product Inventory"),
 *              @OA\Property(property="product_stock", type="integer", description="Product Stock", example="500"),
 *              @OA\Property(property="model_name", type="string", description="Inventory Model Name", example="Inventory Model Name"),
 *              @OA\Property(property="added_by", type="string", description="Inventory Log Added By Name", example="User Name")
 *          ),
 *          @OA\Schema(ref="#/components/schemas/ProductVariant"),
 *          @OA\Schema(ref="#/components/schemas/Stock"),
 *          @OA\Schema(ref="#/components/schemas/StandardTimestampWithoutDeleted"),
 *      }
 *  ))
 * )
 */

class ProductInventory extends Model
{
    use HasFactory, BelongsToPrimaryModel;

    protected $fillable = [
        'product_id',
        'product_detail_id',
        'stock',
        'model_type',
        'model_id'
    ];

    public const MODEL_TYPES = ['Territory', 'Driver', 'Inventory'];

    public const YES = 'YES',
                 NO = 'NO',
                 TERRITORY = 'Territory',
                 DRIVER = 'Driver',
                 INVENTORY = 'Inventory',
                 CATEGORY = 'Category';

    public const MODELCLASS = [
        self::TERRITORY => Territory::class,
        self::DRIVER => DriverUser::class,
        self::INVENTORY => Inventory::class,
        self::CATEGORY => Category::class,
    ];

    public const HUBINVENTORY = 'Hub Inventory',
                 HUBTRANSFER = 'Hub Inventory Transfer',
                 INVENTORYTRANSFER = 'Inventory Transfer To Hub',
                 INVENTORYON = 'Inventory Feature ON',
                 INVENTORYOFF = 'Inventory Feature OFF',
                 BULKTRANSFER ='Bulk Transfer';

    public function model()
    {
        return $this->morphTo();
    }

    public function getRelationshipToPrimaryModel(): string
    {
        return 'product';
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class, 'product_detail_id');
    }

    public function scopeOfProductId($query, $productId)
    {
        return $productId ? $query->where('product_id', $productId) : $query;
    }

    public function scopeOfProductDetailId($query, $productDetailId)
    {
        return $productDetailId ? $query->where('product_detail_id', $productDetailId) : $query;
    }

    public function scopeOfModelType($query, $modelType)
    {
        return $modelType ? $query->where('model_type', $modelType) : $query;
    }

    public function scopeOfModelId($query, $modelId)
    {
        return $modelId ? $query->where('model_id', $modelId) : $query;
    }

    public function scopeInModelId($query, $modelIds)
    {
        return $modelIds ? $query->whereIn('model_id', $modelIds) : $query;
    }

    public function scopeOfWith($query, $with)
    {
        return $with !== null ? $query->with($with) : $query;
    }

    public function scopeHasProduct($query, $checkUnlimited = false)
    {
        return $query->whereHas('product', function ($query) use($checkUnlimited) {
                    $query->where('state', ProductState::ACTIVE);
                    if($checkUnlimited){
                        $query->where('is_unlimited', Product::NO);
                    }
                });
    }


    public function scopeInventoryHasMorph($query, $modelType, $inventoryModelType)
    {
        return $modelType !== ProductInventory::INVENTORY ? $query :
            $query->whereHasMorph('model', [ProductInventory::MODELCLASS[$modelType]],
                        function ($query1) use($inventoryModelType) {
                            $query1->whereHas('modelInventory', function ($query2) use($inventoryModelType) {
                                    $query2->where('model_type', $inventoryModelType);
                                }
                            );
                        }
                    );

    }
}
