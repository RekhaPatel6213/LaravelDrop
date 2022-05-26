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

/**
 * * @OA\Schema(
 *   schema="HubBannerList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/HubBanner")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 * )
 *
 * @OA\Schema(
 *   schema="HubBanner",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/HubBannerInputData"),
 *      @OA\Schema(
 *          @OA\Property(property="id",type="integer", format="int32", description="ID",example="1"),
 *          @OA\Property(property="banner_image", type="string", description="Banner image", example="https://example.com/image.png"),
 *      )
 *   }
 * )
 *
 * @OA\Schema(
 *  schema="HubBannerPatchData",
 *      @OA\Property(property="status", type="string", description="Banner status", example="ACTIVE", enum={"ACTIVE","INACTIVE"}),
 * )
 *
 * @OA\Schema(
 *   schema="HubBannerData",
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/HubBanner"))
 * )
 *
 * @OA\Schema(schema="HubBannerInputData",
 *      @OA\Property(property="banner_image", type="file", description="Banner Image"),
 *      @OA\Property(property="type", type="string", description="Type", example="MESSAGE", enum={"MESSAGE", "GALLERY"}),
 *      @OA\Property(property="title", type="string", description="Header Name", example="Category"),
 *      @OA\Property(property="description", type="string", description="Description", example="Redirect to category"),
 *      @OA\Property(property="redirect_type", type="string", description="Redirect Type", example="Category", enum={"CATEGORY","MENU", "DEAL" , "DEAL-MENU", "REWARD-MENU", "BRAND", "PRODUCT", "REFER-FRIEND", "SHOP-INFO", "NO-REDIRECTION"}),
 *      @OA\Property(property="redirect_detail", type="array", description="Redirect Detail By Type", @OA\Items(type="string", example="1")),
 *      @OA\Property(property="frequency", type="string", description="Frequency", example="RECURRING", enum={"RECURRING", "LIMITED"}),
 *      @OA\Property(property="days", type="array",  @OA\Items(type="integer", example="1")),
 *      @OA\Property(property="from_time", type="string", description="From Time", example=""),
 *      @OA\Property(property="to_time", type="string", description="To Time", example=""),@OA\Property(property="start_date", type="string", description="Start Date", example=""),
 *      @OA\Property(property="end_date", type="string", description="End Date", example=""),
 *  )
 *
 * @OA\RequestBody(
 *     request="HubBannerRequest",
 *     description="Banner Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/HubBannerInputData")
 *  )
 *
 * @OA\Schema(schema="HubBannerSortsOn", type="array",
 *     @OA\Items(type="string", enum={"title"})
 * )
 *
 *  @OA\Schema(
 *   schema="RedirectDetail",
 *   required={"data"},
 *   @OA\Property(property="data", type="array",
 *     @OA\Items(type="string", description="Redirect Type", example="Redirect Detail")
 *   )
 * )
 *
 */
/**
 * Class Banner.
 *
 * @package namespace App\Models\Hub;
 */
class Banner extends Model implements Transformable, HasMedia
{
    use TransformableTrait, DispensaryTrait, InteractsWithMedia, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'title',
        'description',
        'redirect_type',
        'redirect_detail',
        'frequency',
        'status',
        'days',
        'from_time',
        'to_time',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'days' => 'array',
    ];

    public const ACTIVE = 'ACTIVE', INACTIVE = 'INACTIVE', RECURRING = 'RECURRING', LIMITED = 'LIMITED', MESSAGE = 'MESSAGE', GALLERY = 'GALLERY', MEDIATYPE = 'banner';
    const PRODUCT = 'Products', CATEGORY = 'Categories', DEAL = 'Deals', BRAND = 'Brands';

    const DEFAULT_LIST_ORDER = 'desc';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banner_image')
            ->useDisk('DO')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(50)
            ->height(50);
    }
}
