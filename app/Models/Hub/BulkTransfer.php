<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *   schema="BulkProducts",
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/BulkProductsList")
 * )
 * 
 * @OA\Schema(
 *   schema="BulkProductsList",
 *   @OA\Property(property="product_id", type="object", 
 *      allOf={
 *          @OA\Schema(ref="#/components/schemas/BulkProductDetails"),
 *          @OA\Schema(ref="#/components/schemas/BulkInventoryStocks"),
 *          @OA\Schema(
 *              @OA\Property(property="logo", type="string", description="Product Logo URL", example="http://example.com/example-thumb.jpg"),
 *              @OA\Property(property="isPrePack", type="string", description="Is Pre-Packaged Product", example="false", enum={"true", "false"}),
 *              @OA\Property(property="product_detail", type="array", @OA\Items(ref="#/components/schemas/BulkProductDetailData"))    
 *          )
 *      }
 *   )
 * )
 *
 * @OA\Schema(
 *   schema="BulkProductDetailData",
 *   required={"data"},
 *   allOf={
 *      @OA\Schema(
 *          @OA\Property(property="variant", type="string", description="Variant Name", example="Variant Name"),
 *          @OA\Property(property="product_detail_id", type="integer", format="int32", description="Product Detail Id", example="1"),
 *      ),
 *      @OA\Schema(ref="#/components/schemas/BulkInventoryStocks")
 *  }
 * )
 * 
 * @OA\Schema(
 *   schema="BulkInventoryStocks",
 *   required={"data"},
 *   @OA\Property(property="from_inventory_stock", type="integer", format="int32", description="From Inventory Stock", example="10"),
 *   @OA\Property(property="to_inventory_stock", type="integer", format="int32", description="To Inventory Stock", example="20")
 * )
 * 
 * 
 * @OA\Schema(
 *   schema="BulkTransferDetail",
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/BulkTransferSingleDetail")
 * )
 * 
 * @OA\Schema(
 *   schema="BulkTransferSingleDetail",
 *   allOf={
 *      @OA\Schema(
 *          @OA\Property(property="id", type="integer", format="int32", description="Inventory Id", example="1"),
 *          @OA\Property(property="dispensary_id", type="integer", format="int32", description="Dispensary Id", example="1"),
 *          @OA\Property(property="fromInventory", type="object", ref="#/components/schemas/BulkInventoryDetails"),
 *          @OA\Property(property="toInventory", type="object", ref="#/components/schemas/BulkInventoryDetails"),
 *          @OA\Property(property="products", type="object", ref="#/components/schemas/BulkProductSingleDetails")
 *      ),
 *      @OA\Schema(ref="#/components/schemas/StandardTimestampWithoutDeleted")
 *   }
 * )
 *
 * @OA\Schema(
 *   schema="BulkInventoryDetails",
 *   @OA\Property(property="id", type="integer", format="int32", description="Inventory Id", example="1"),
 *   @OA\Property(property="name", type="string", description="Inventory Name", example="Inventory Name")
 * )
 *
 * @OA\Schema(
 *   schema="BulkProductSingleDetails",
 *   @OA\Property(property="product_id", type="array", @OA\Items(
 *      allOf={
 *          @OA\Schema(ref="#/components/schemas/BulkProductDetails"),
 *          @OA\Schema(
 *              @OA\Property(property="variant", type="string", description="Variant Name", example="Variant Name"),
 *              @OA\Property(property="transferred_stock", type="integer", format="int32", description="Transferred Stock", example="5"),
 *              @OA\Property(property="from_current_stock", type="integer", format="int32", description="From Inventory Current Stock", example="15"),
 *              @OA\Property(property="to_current_stock", type="integer", format="int32", description="To Inventory Current Stock", example="7")
 *          )
 *      }
 *   ))
 * )
 * 
 * @OA\Schema(
 *   schema="BulkProductDetails",
 *   allOf={
 *      @OA\Schema(
 *          @OA\Property(property="id", type="integer", format="int32", description="Product Id", example="1"),
 *          @OA\Property(property="product_detail_id", type="integer", format="int32", description="Product Detail Id", example="2"),
 *          @OA\Property(property="name", type="string", description="Product Name", example="Product Name"),
 *          @OA\Property(property="quantity_type", type="string", description="Product Quantity Type", example="UNITS")
 *      ),
 *      @OA\Schema(ref="#/components/schemas/BulkInventoryStocks")
 *   }
 * )
 *
 * @OA\Schema(
 *   schema="BulkTransferInputData",
 *   required={"data"},
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/BulkTransferInventoryIds"),
 *      @OA\Schema(
 *          @OA\Property(property="products", type="array", @OA\Items(
 *              @OA\Property(property="product_id", type="integer", format="int32", description="Product Id", example="1"),
 *              @OA\Property(property="product_details", type="array", @OA\Items(ref="#/components/schemas/BulkProductDetailWithStock"))
 *          ))
 *      )
 *   }
 * )
 * 
 * @OA\Schema(
 *   schema="BulkProductDetailWithStock",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/BulkProductDetailData"),
 *      @OA\Schema(ref="#/components/schemas/BulkTransferStock")
 *  }
 * )
 *
 * @OA\Schema(
 *   schema="BulkTransferStock",
 *   @OA\Property(property="stock", type="integer", format="int32", description="Transfer Stock", example="5")
 * )
 * 
 * 
 * @OA\Schema(
 *   schema="BulkTransferInventoryIds",
 *   required={"data"},
 *   @OA\Property(property="from_inventory_id", type="integer", format="int32", description="From Inventory Id", example="1"),
 *   @OA\Property(property="to_inventory_id", type="integer", format="int32", description="To Inventory Id", example="2")
 * )
 */
class BulkTransfer extends Model
{
    use HasFactory, DispensaryTrait, SoftDeletes;

    protected $fillable = [
        'from_inventory_id',
        'to_inventory_id',
        'dispensary_id',
        'products'
    ];

    protected $casts = [
        'products' => 'array'
    ];

    public const UNALLOCATED_ID = -1;

    public function fromInventory()
    {
        return $this->belongsTo(Inventory::class, 'from_inventory_id');
    }

    public function toInventory()
    {
        return $this->belongsTo(Inventory::class, 'to_inventory_id');
    }
}
