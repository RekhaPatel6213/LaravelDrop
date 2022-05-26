<?php

namespace App\Models\Hub;

use App\Http\Traits\DispensaryTrait;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWallet;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Vanilo\Category\Traits\HasTaxons;
use Vanilo\Product\Models\Product as BaseProduct;
use Vanilo\Product\Models\ProductState;

/**
 * @OA\Schema(schema="ProductState", type="array",
 *     @OA\Items(type="string", enum={"active", "inactive"})
 * )
 *
 * @OA\Schema(schema="ProductSortsOn", type="array",
 *     @OA\Items(type="string", enum={"name", "brand", "price", "stock", "state"})
 * )
 *
 * @OA\Schema(
 *   schema="ProductList",
 *   @OA\Property(property="data", type="object",
 *      @OA\Property(property="priority", type="object",
 *          @OA\Property(property="category", type="object", ref="#/components/schemas/ProductCategoryList" )
 *      )
 *   )
 * )
 *
 * @OA\Schema(
 *   schema="ProductCategoryList",
 *   required={"data"},
 *   type="array",
 *   @OA\Items(
 *    allOf={
 *      @OA\Schema(ref="#/components/schemas/ProductCommonList"),
 *      @OA\Schema(ref="#/components/schemas/ProductCommonInputDataOne"),
 *      @OA\Schema(
 *          @OA\Property(property="category", type="string", description="Category Name", example="Category"),
 *           @OA\Property(property="quantity_type", type="string", description="Product Quantity Type", example="PRE-PACKAGED", enum={"PRE-PACKAGED", "GRAMS", "UNITS"}),
 *          @OA\Property(property="variants", type="array",  @OA\Items(type="integer", example="1G,2G")),
 *          @OA\Property(property="stocks", type="array",  @OA\Items(type="integer", example="10.00,20.00")),
 *          @OA\Property(property="prices", type="array",  @OA\Items(type="integer", example="10.00,20.00")),
 *          @OA\Property(property="wholesale_prices", type="array",  @OA\Items(type="integer", example="10.00,20.00")),
 *          @OA\Property(property="unallocatedQuantity",type="integer",format="int32", description="Product Unallocated Quantity", example="100"),
 *          @OA\Property(property="priority",type="integer",format="int32", description="Product Priority", example="1")
 *      )
 *     }
 *    )
 * )
 *
 * @OA\Schema(
 *   schema="ProductListDetail",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Product")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 * )
 *
 * @OA\Schema(
 *   schema="ProductGetDetail",
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/Product")
 * )
 *
 * @OA\Schema(
 *   schema="Product",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/ProductCommonList"),
 *      @OA\Schema(ref="#/components/schemas/ProductCommonInputDataOne"),
 *      @OA\Schema(ref="#/components/schemas/ProductCommonInputDataTwo"),
 *      @OA\Schema(ref="#/components/schemas/ProductCommonInputDataFour"),
 *      @OA\Schema(
 *          @OA\Property(property="id",type="integer",format="int32", description="Product Id", example="1"),
 *          @OA\Property(property="slug", type="string", description="Product Slug", example="product-name"),
 *          @OA\Property(property="sku", type="string", description="Product Sku", example="I74X2NTA"),
 *          @OA\Property(property="state",type="string",description="Product status", example="active"),
 *          @OA\Property(property="logo", type="string", description="Product Logo URL", example="http://example.com/example-thumb.jpg"),
 *          @OA\Property(property="priority", type="integer", format="int32", description="Product Priority", example="1"),
 *          @OA\Property(property="taxons", type="object", @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/MainTaxonData"))),
 *          @OA\Property(property="product_details", type="object", @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ProductPriceData"))),
 *      ),
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp")
 *   }
 * )
 *
 * @OA\Schema(
 *  schema="ProductCommonList",
 *      @OA\Property(property="id",type="integer", format="int32", description="Product Id", example="1"),
 *      @OA\Property(property="logo", type="string", description="Product Logo URL", example="http://example.com/example-thumb.jpg"),
 *      @OA\Property(property="priority", type="integer", format="int32", description="Product Priority", example="1")
 * )
 *
 * @OA\Schema(
 *  schema="ProductCommonInputDataOne",
 *      @OA\Property(property="name", type="string", description="Product Name", example="Product Name"),
 *      @OA\Property(property="brand", type="string", description="Product Brand Name", example="Product Brand Name")
 * )
 *
 * @OA\Schema(
 *  schema="ProductCommonInputDataTwo",
 *      @OA\Property(property="description", type="string", description="Product Description", example="Product Description"),
 *      @OA\Property(property="strain_type", type="string", description="Strain Type", example="INDICA", enum={"INDICA", "SATIVA", "HYBRID", "CBD HIGH"}),
 *      @OA\Property(property="product_type", type="string", description="Product Type", example="MEDICAL", enum={"MEDICAL", "RECREATIONAL"}),
 *      @OA\Property(property="thc", type="integer" ,format="int32", description="THC", example="1"),
 *      @OA\Property(property="thc_type", type="string", description="THC Type", example="PERCENT", enum={"PERCENT", "MG"}),
 *      @OA\Property(property="cbd", type="integer" ,format="int32", description="CBD", example="1"),
 *      @OA\Property(property="cbd_type", type="string", description="CBD Type", example="PERCENT", enum={"PERCENT", "MG"}),
 *      @OA\Property(property="cbn", type="integer" ,format="int32", description="CBN", example="1"),
 *      @OA\Property(property="cbn_type", type="string", description="CBN Type", example="PERCENT", enum={"PERCENT", "MG"}),
 *      @OA\Property(property="is_featured", type="string", description="Featured Product", example="NO", enum={"YES", "NO"}),
 *      @OA\Property(property="state", type="string", description="Product state", example="active", enum={"active","inactive"})
 * )
 *
 * @OA\Schema(
 *  schema="ProductCommonInputDataThree",
 *      @OA\Property(property="price", type="integer" ,format="int32", description="Product Price", example="150"),
 *      @OA\Property(property="wholesale_price", type="integer" ,format="int32", description="Wholesale Product Price", example="100"),
 *      @OA\Property(property="stock", type="integer" ,format="int32", description="Product Stock", example="50"),
 * )
 *
 * @OA\Schema(
 *  schema="ProductCommonInputDataFour",
 *      @OA\Property(property="quantity_type", type="string", description="Product Quantity Type", example="PRE-PACKAGED", enum={"PRE-PACKAGED", "GRAMS", "UNITS"}),
 *      @OA\Property(property="is_unlimited", type="string", description="Unlimited Quantity Product", example="NO", enum={"YES", "NO"}),
 * )
 *
 * @OA\Schema(
 *  schema="ProductCommonInputDataFive",
 *      @OA\Property(property="category_id", type="string", description="Category Id", example="1"),
 *      @OA\Property(property="product_details", type="array", description="Product Price Stock Details", @OA\Items(ref="#/components/schemas/ProductPriceData", collectionFormat="multi")),
 * )
 *
 * @OA\Schema(
 *   schema="ProductInputData",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/ProductCommonInputDataOne"),
 *      @OA\Schema(ref="#/components/schemas/ProductCommonInputDataTwo"),
 *      @OA\Schema(ref="#/components/schemas/ProductCommonInputDataFour"),
 *      @OA\Schema(ref="#/components/schemas/ProductCommonInputDataThree"),
 *      @OA\Schema(
 *          @OA\Property(property="logo", type="file", description="Product Logo"),
 *          @OA\Property(property="category_id", type="string", description="Category Id", example="1"),
 *          @OA\Property(property="product_details", type="array", description="Product Price Stock Details", @OA\Items(ref="#/components/schemas/ProductPriceData", collectionFormat="multi")),
 *      )
 *   }
 * )
 *
 * @OA\Schema(
 *   schema="ProductPriceData",
 *   required={"data"},
 *      @OA\Property(property="id",type="integer", format="int32", description="Product Id", example="1"),
 *      @OA\Property(property="variant_id", type="integer" ,format="int32", description="Product Variant Id", example="1"),
 *      @OA\Property(property="wholesale_price", type="integer" ,format="int32", description="Wholesale Product Price", example="100"),
 *      @OA\Property(property="price", type="integer" ,format="int32", description="Product Price", example="150"),
 *      @OA\Property(property="stock", type="integer" ,format="int32", description="Product Stock", example="50")
 * )
 *
 * @OA\Schema(
 *  schema="ProductPatchData",
 *      @OA\Property(property="state", type="string", description="Product state", example="active", enum={"active","inactive"}),
 * )
 *
 * @OA\Schema(
 *   schema="VariantList",
 *   @OA\Property(property="data", type="array", @OA\Items(
 *      @OA\Property(property="id",type="integer", format="int32", description="Product Variant Id", example="1"),
 *      @OA\Property(property="name", type="string", description="Product Variant Name", example="Product Variant Name"),
 *      @OA\Property(property="taxonomy_id", type="string", description="Taxonomy Id", example="1"),
 *      @OA\Property(property="attribute", type="string", description="Attribute Type", example="PRE-PACKAGED", enum={"PRE-PACKAGED", "GRAMS"}),
 *      @OA\Property(property="type", type="string", description="Variant Type", example="YES", enum={"YES", "NO"}),
 *      @OA\Property(property="quantity",type="integer",format="int32", description="Variant Quantity", example="100"),
 *      @OA\Property(property="limit_quantity",type="integer",format="int32", description="Variant Limit Quantity", example="100"),
 *      @OA\Property(property="priority",type="integer",format="int32", description="Variant Priority", example="1")
 *    )
 *   )
 * )
 *
 * @OA\Schema(schema="ProductImportData",
 *      @OA\Property(property="csv_import", type="file", description="Please select csv file")
 * )
 *
 * @OA\Schema(
 *   schema="ImportDataList",
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ImportData"))
 * )
 *
 * @OA\Schema(
 *   schema="ImportData",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/ImportDetailOne"),
 *      @OA\Schema(ref="#/components/schemas/StandardTimestampWithoutDeleted")
 *   }
 * )
 *
 * @OA\Schema(
 *   schema="ImportDetailOne",
 *   @OA\Property(property="id",type="integer", format="int32", description="Import Id", example="1"),
 *   @OA\Property(property="new_items", type="integer", format="int32", description="Product New item Count", example="1"),
 *   @OA\Property(property="existing_items", type="integer", format="int32", description="Product Existing Items Count", example="3"),
 *   @OA\Property(property="total_price", type="integer", format="int32", description="Product Total Price", example="100.0"),
 *   @OA\Property(property="user", type="string", description="Import User Name", example="Dispensary User")
 * )
 *
 * @OA\Schema(
 *   schema="ImportDetails",
 *   @OA\Property(property="data", type="object",
 *      allOf={
 *          @OA\Schema(ref="#/components/schemas/ImportDetailOne"),
 *          @OA\Schema(
 *              @OA\Property(property="data", type="object", ref="#/components/schemas/ImportDetailData")
 *          ),
 *          @OA\Schema(ref="#/components/schemas/StandardTimestampWithoutDeleted")
 *      }
 *   )
 * )
 *
 * @OA\Schema(
 *   schema="ImportDetailData",
 *   required={"data"},
 *   type="array",
 *   @OA\Items(
 *    allOf={
 *      @OA\Schema(ref="#/components/schemas/ProductCommonInputDataOne"),
 *      @OA\Schema(ref="#/components/schemas/ProductCommonInputDataThree"),
 *      @OA\Schema(ref="#/components/schemas/ProductCommonInputDataFour"),
 *      @OA\Schema(ref="#/components/schemas/ProductCommonInputDataFive"),
 *      @OA\Schema(ref="#/components/schemas/ImortExtraData"),
 *      @OA\Schema(
 *          @OA\Property(property="logo", type="string", description="Product Logo URL", example="http://example.com/example-thumb.jpg"),
 *          @OA\Property(property="is_new", type="integer", format="int32", description="New Product", example="1"),
 *          @OA\Property(property="product_id",type="integer", format="int32", description="Existing Product Id", example="1"),
 *          @OA\Property(property="category_id", type="string", description="Category Id", example="1"),
 *          @OA\Property(property="product_details", type="array", description="Product Price Stock Details", @OA\Items(ref="#/components/schemas/ImportProductPriceData", collectionFormat="multi"))
 *      )
 *     }
 *    )
 * )
 *
 * @OA\Schema(
 *   schema="ImortExtraData",
 *       @OA\Property(property="total", type="integer", format="int32", description="Product Total Price", example="100.0"),
 *        @OA\Property(property="previous_stock",type="integer", format="int32", description="Existing Product Stock", example="100"),
 * )
 *
 * @OA\Schema(
 *   schema="ImportProductPriceData",
 *      allOf={
 *          @OA\Schema(ref="#/components/schemas/ProductPriceData"),
 *          @OA\Schema(ref="#/components/schemas/ImortExtraData")
 *      }
 * )
 *
 * @OA\Schema(
 *   schema="ImportInputData",
 *      @OA\Property(property="product", type="array", @OA\Items(
 *          @OA\Property(property="is_unlimited", type="string", description="Unlimited Quantity Product", example="NO", enum={"YES", "NO"}),
 *          @OA\Property(property="stock", type="integer", format="int32", description="Product Stock", example="10"),
 *          @OA\Property(property="product_details", type="array", description="Product Stock Details", @OA\Items(
 *              @OA\Property(property="previous_stock",type="integer", format="int32", description="Existing Product Stock", example="100")
 * )),
 *      ))
 * )
 *
 * @OA\Schema(
 *   schema="ImportDetailView",
 *   @OA\Property(property="data", type="object",
 *      allOf={
 *          @OA\Schema(ref="#/components/schemas/ImportDetailOne"),
 *          @OA\Schema(
 *              @OA\Property(property="data", type="object",
 *                  @OA\Property(property="priority", type="object",
 *                      @OA\Property(property="category", type="object", ref="#/components/schemas/ImportDetailData" )
 *                  )
 *              )
 *          ),
 *          @OA\Schema(ref="#/components/schemas/StandardTimestampWithoutDeleted")
 *      }
 *   )
 * )
 */
class Product extends BaseProduct implements HasMedia, Wallet
{
    use InteractsWithMedia; // Spatie package's default trait
    use DispensaryTrait; // Dispensary trait
    use HasTaxons;
    use SoftDeletes;
    use LogsActivity;
    use HasWallet;

    protected static $recordEvents = [];

    protected $fillable = [
        'name',
        'description',
        'dispensary_id',
        'brand',
        'strain_type',
        'product_type',
        'quantity_type',
        'is_unlimited',
        'thc',
        'thc_type',
        'cbd',
        'cbd_type',
        'cbn',
        'cbn_type',
        'state',
        'stock',
        'wholesale_price',
    ];

    public const INDICA = 'INDICA';
    public const SATIVA = 'SATIVA';
    public const HYBRID = 'HYBRID';
    public const CBDHIGH = 'CBD HIGH';
    public const RECREATIONAL = 'RECREATIONAL';
    public const MEDICAL = 'MEDICAL';
    public const PERCENT = 'PERCENT';
    public const MG = 'MG';
    public const YES = 'YES';
    public const NO = 'NO';
    public const UNITS = 'UNITS';
    public const GRAMS = 'GRAMS';
    public const PREPACKAGED = 'PRE-PACKAGED';
    public const UNLIMITED = 'unlimited';
    public const MEDIATYPE = 'logo';
    public const PDF = 'pdf';
    public const CSV = 'csv';
    public const STOCK = 'stock';

    public const SEARCH_FIELDS = ['products.name', 'products.brand'];
    public const SORTS_ON = ['name', 'brand', 'price', 'stock', 'state'];
    public const THC_TYPES = [self::PERCENT, self::MG];
    public const QUANTITY_TYPES = [self::UNITS, self::GRAMS, self::PREPACKAGED];

    public const PRODUCT_INVENTORY_HUB = 'product_inventory_hub';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIATYPE)
            ->useDisk('DO')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(50)
            ->height(50);
    }

    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class, 'product_id', 'id');
    }

    public function inventories()
    {
        return $this->hasMany(ProductInventory::class, 'product_id', 'id');
    }

    public static function maxPriorityOfCategoryProduct($categoryId)
    {
        $dispensaryId = tenant('id');
        $count = self::with(['taxons'])
            ->whereHas('taxons', function ($query) use ($categoryId) {
                $query->where('id', $categoryId);
            })
            ->where('dispensary_id', $dispensaryId)
            ->where('state', ProductState::ACTIVE)
            ->max('priority');

        return $count ? $count + 1 : 1;
    }

    public function dealModels()
    {
        return $this->morphToMany(Deal::class, 'model','deal_models');
    }
    
    public function scopeOfActiveState($query)
    {
        return $query->where('state', ProductState::ACTIVE);
    }

    public function scopeOfUnlimited($query, $unlimited)
    {
        return $unlimited ? $query->where('is_unlimited', $unlimited) : $query;
    }

    public function scopeHasInventory($query, $modelType, $inventoryModelType)
    {
        return $query->whereHas('inventories', function ($query) use ($modelType, $inventoryModelType) {
            if ($modelType) {
                $query->ofModelType($modelType)->inventoryHasMorph($modelType, $inventoryModelType);
            }
            $query->where('stock', '>=', 0);
        });
    }

    public function scopeHasTaxon($query, $taxonId)
    {
        return $query->whereHas('taxons', function ($query) use ($taxonId) {
            if ($taxonId) {
                $query->where('id', $taxonId);
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
