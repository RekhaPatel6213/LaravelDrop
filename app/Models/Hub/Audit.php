<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Admin\Dispensary\DispensaryUser;
use App\Models\Hub\ProductInventory;

/**
 * @OA\Schema(
 *  schema="AuditProducts",
 *  @OA\Property(property="data", type="object", 
 *      @OA\Property(property="product_id", type="array", @OA\Items(
 *          allOf={
 *              @OA\Schema(ref="#/components/schemas/AuditProductData"),
 *              @OA\Schema(
 *                  @OA\Property(property="productDetail", type="array", @OA\Items(
 *                      allOf={
 *                          @OA\Schema(ref="#/components/schemas/AuditProductDetailData"),
 *                          @OA\Schema(
 *                              @OA\Property(property="total_allocated_stock", type="integer", format="int32", description="Product Total Allocated Stock", example="20")
 *                          )  
 *                      }
 *                  ))
 *              )
 *          }
 *      ))
 *  )
 * )
 *
 * @OA\Schema(
 *  schema="AuditProductData",
 *  required={"data"},
 *  @OA\Property(property="product_id", type="integer", format="int32", description="Product Id", example="1"),
 *  @OA\Property(property="product_name", type="string", description="Product Name", example="Product Name"),
 *  @OA\Property(property="brand", type="string", description="Product Brand Name", example="Product Brand Name"),
 *  @OA\Property(property="quantity_type", type="string", description="Product Quantity Type", example="UNITS/GRAMS")
 * )
 *
 * @OA\Schema(
 *  schema="AuditProductDetailData",
 *  required={"data"},
 *  @OA\Property(property="product_detail_id", type="integer", format="int32", description="Product Detail Id", example="1"),
 *  @OA\Property(property="variant", type="string", description="Variant Name", example="Variant Name"),
 *  @OA\Property(property="is_unlimited", type="string", description="Unlimited Quantity Product", example="NO", enum={"YES", "NO"}),
 *  @OA\Property(property="stock", type="integer", format="int32", description="Product Stock", example="100"),
 *  @OA\Property(property="allocated_stock", type="integer", format="int32", description="Product Allocated Stock", example="20")   
 * )
 *
 * @OA\Schema(
 *  schema="AuditInputData",
 *  required={"data"},
 *  @OA\Property(property="model_type", type="string", description="Model Type", example="Territory", @OA\Schema(type="string", enum={"Inventory","Territory","Driver","Category"})),
 *  @OA\Property(property="model_id", type="integer", description="Model Id", example="1", @OA\Schema(type="integer")),
 *  @OA\Property(property="products", type="array", @OA\Items(
 *      allOf={
 *          @OA\Schema(ref="#/components/schemas/AuditProductData"),
 *          @OA\Schema(
 *              @OA\Property(property="product_details", type="array", @OA\Items(
 *                  allOf={
 *                      @OA\Schema(ref="#/components/schemas/AuditProductDetailData"),
 *                      @OA\Schema(
 *                          @OA\Property(property="new_stock", type="integer", format="int32", description="Product New Stock", example="90"),
 *                          @OA\Property(property="difference_stock", type="integer", format="int32", description="Product Difference Stock", example="-10"),
 *                          @OA\Property(property="reason", type="string", description="Audit Reason", example="Product loss due to damage")
 *                      )  
 *                  }
 *              ))
 *          )
 *      }
 *  ))
 * )
 * 
 * @OA\Schema(schema="AuditSortsOn", type="array",
 *  @OA\Items(type="string", enum={"model_type", "created_by", "created_at"})
 * )
 * 
 * @OA\Schema(
 *  schema="AuditList",
 *  @OA\Property(property="data", type="array", @OA\Items(
 *      allOf={
 *          @OA\Schema(ref="#/components/schemas/AuditData"),
 *          @OA\Schema(
 *              @OA\Property(property="model_name", type="string", description="Model Type Name", example="Model Type Name"),
 *              @OA\Property(property="model_id", type="integer", description="Model Id", example="1"), 
 *          ),
 *          @OA\Schema(ref="#/components/schemas/StandardTimestampWithoutDeleted") 
 *      }
 *  )),
 *  @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 * )
 * 
 * @OA\Schema(
 *  schema="AuditData",
 *  @OA\Property(property="id", type="integer", format="int32", description="Audit Id", example="1"),
 *  @OA\Property(property="model_type", type="string", description="Audit Type", example="Inventory/Territory/Driver/Category Name"),
 *  @OA\Property(property="created_by", type="string", description="Dispensary User Name", example="Jone Duo")
 * )
 * 
 * @OA\Schema(
 *  schema="AuditDetail",
 *  @OA\Property(property="products", type="array", @OA\Items(
 *      allOf={
 *          @OA\Schema(ref="#/components/schemas/AuditData"),
 *          @OA\Schema(ref="#/components/schemas/AuditInputData") 
 *      }
 *  ))
 * )
 */

class Audit extends Model
{
    use DispensaryTrait, SoftDeletes;

    protected $fillable = [
        'dispensary_id',
        'products',
        'model_type',
        'model_id'
    ];

    protected $casts = [
        'products' => 'array'
    ];

    public const SEARCH_FIELDS = ['id', 'model_type', 'model_id'];

    public function model()
    {
        return $this->morphTo();
    }

    public function dispensaryUser()
    {
        return $this->belongsTo(dispensaryUser::class, 'created_by');
    }

    public function scopeOfModelType($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    public function scopeInModelId($query, $modelIds)
    {
        return $query->whereIn('model_id', $modelIds);
    }

    public static function boot() {
        parent::boot();
        static::creating(function($audit) { // before create() method call
            $audit->setAttribute('created_by', Auth()->user()->id);
        });
    }
}
