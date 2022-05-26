<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/** 
 * @OA\Schema(schema="BulkTemplateSortsOn", type="array",
 *     @OA\Items(type="string", enum={"name", "from_inventory_id", "to_inventory_id"})
 * )
 *
 * @OA\Schema(
 *   schema="BulkTemplateList",
 *   @OA\Property(property="data", type="array", @OA\Items(
 *      allOf={
 *          @OA\Schema(ref="#/components/schemas/BulkTemplateSingleDetail"),
 *          @OA\Schema(ref="#/components/schemas/StandardTimestamp")
 *      }
 *    ))
 * )
 *
 * @OA\Schema(
 *   schema="BulkTemplateInputData",
 *   allOf={
 *      @OA\Schema(
 *          @OA\Property(property="name", type="string", description="Bulk Template Name", example="Template Name"),
 *          @OA\Property(property="products", type="array", @OA\Items(ref="#/components/schemas/BulkTemplateProduct")),
 *      ),
 *      @OA\Schema(ref="#/components/schemas/BulkTransferInventoryIds"),
 *   }
 * )
 * 
 * @OA\Schema(
 *   schema="BulkTemplateProduct",
 *   @OA\Property(property="product_id", type="integer", format="int32", description="Product Id", example="1"),
 *   @OA\Property(property="isPrePack", type="string", description="Is Pre-Packaged Product", example="false", enum={"true", "false"}),
 *   @OA\Property(property="quantity_type", type="string", description="Product Quantity Type", example="UNITS"),
 *   @OA\Property(property="product_detail", type="array", @OA\Items(ref="#/components/schemas/BulkProductDetailWithStock"))
 * )
 *
 * @OA\Schema(
 *   schema="BulkTemplateSingle",
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/BulkTemplateSingleDetail")
 * )
 *
 * @OA\Schema(
 *   schema="BulkTemplateSingleDetail",
 *   allOf={
 *      @OA\Schema(
 *          @OA\Property(property="id", type="integer", format="int32", description="Inventory Id", example="1"),
 *          @OA\Property(property="dispensary_id", type="integer", format="int32", description="Dispensary Id", example="1")
 *      ),
 *      @OA\Schema(ref="#/components/schemas/BulkTemplateInputData"),
 *   }
 * )
 * 
 * @OA\Schema(
 *   schema="BulkTemplatePatchData",
 *   @OA\Property(property="products", type="array", @OA\Items(ref="#/components/schemas/BulkTemplateProduct"))
 * )
 */

class BulkTemplate extends Model
{
    use HasFactory, DispensaryTrait, SoftDeletes;

    protected $fillable = [
        'dispensary_id',
        'name',
        'from_inventory_id',
        'to_inventory_id',
        'products'
    ];

    protected $casts = [
        'products' => 'array'
    ];

    public const SEARCH_FIELDS = ['name', 'from_inventory_id', 'to_inventory_id', 'products'];
}
