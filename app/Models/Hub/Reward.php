<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Http\Traits\DispensaryTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @OA\Schema(
 *   schema="RewardList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Reward")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 *  )
 * @OA\Schema(
 *   schema="Reward",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/RewardInputData"),
 *      @OA\Schema(
 *          @OA\Property(property="id",type="integer",format="int32",description="ID", example="1")
 *      ),
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp"),
 *   }
 *  )
 *
 * @OA\Schema(schema="RewardInputData",
 *     @OA\Property(property="name", type="string", description="Name", example="Reward"),
 *     @OA\Property(property="points", type="integer" ,format="int32", description="Reward Points", example="150"),
 *     @OA\Property(property="is_inventory", type="string", description="Connect Inventory", example="NO", enum={"YES", "NO"}),
 *     @OA\Property(property="discount_type", type="string", description="Discount Type", example="percent", enum={"PERCENT", "FIXED"}),
 *     @OA\Property(property="discount_price", type="integer" ,format="int32", description="Discount Price", example="150"),
 *     @OA\Property(property="product_id",type="integer", format="int32", description="Product Id", example=""),
 *     @OA\Property(property="product_detail_id",type="integer", format="int32", description="Product Detail Id", example=""),
 *     @OA\Property(property="description", type="string", description="Reward Description", example="Reward Description"),
 *     @OA\Property(property="is_birthday", type="string", description="Birthday Reward", example="NO", enum={"YES", "NO"}),
 *      @OA\Property(property="logo", type="file", description="Reward Logo URL", example="http://example.com/example-thumb.jpg"),
 *  )
 *
 * @OA\RequestBody(
 *     request="RewardRequest",
 *     description="Reward Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/RewardInputData")
 *  )
 *
 * @OA\Schema(schema="RewardSortsOn", type="array",
 *     @OA\Items(type="string", enum={"name", "slug", "sku", "id"})
 * )
 */

/**
 * Class Rewards.
 *
 * @package namespace App\Models\Hub;
 */
class Reward extends Model implements Transformable, HasMedia
{
    use TransformableTrait, DispensaryTrait, InteractsWithMedia, SoftDeletes, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sku',
        'points',
        'is_inventory',
        'discount_type',
        'discount_price',
        'product_id',
        'product_detail_id',
        'description',
        'is_birthday'
    ];

    public const PERCENT = 'PERCENT',
        AMOUNT = 'FIXED',
        YES = 'YES',
        NO = 'NO';
    const POINTS = ['150', '100','200','250','300','400','500','600','700','800','900','1000','1500','2000','2500','3000','3500','4000','4500','5000'];
    const DEFAULT_LIST_ORDER = 'desc';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->useDisk('DO')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(50)
            ->height(50);
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}