<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @OA\Schema(
 *   schema="PageList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Page")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 *  )
 * @OA\Schema(
 *   schema="Page",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/PageInputData"),
 *      @OA\Schema(
 *          @OA\Property(property="id",type="integer",format="int32",description="ID", example="1")
 *      ),
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp"),
 *   }
 *  )
 *
 * @OA\Schema(schema="PageInputData",
 *      @OA\Property(property="name", type="string", description="Title", example="Privacy Policy"),
 *      @OA\Property(property="group", type="string", description="TERM, POLICY,OTHER", example="TERM"),
 *      @OA\Property(property="html_content", type="string", description="Html Content", example="<p><strong>Introduction</strong></p><p>Drop Technologies, Inc. (&ldquo;Drop&rdquo;) understands and respects our users&rsquo; need for privacy. This Privacy Notice (&ldquo;Notice&rdquo;) describes the types of information we collect, the purposes for which it is used, and the choices you have with respect to its use.</p>"),
 *      @OA\Property(property="priority", type="string", description="Set Page Priority", example="1"),
 *      @OA\Property(property="sub_id", type="string", description="Set Parent Page ID", example="1"),
 *  )
 *
 * @OA\RequestBody(
 *     request="PageRequest",
 *     description="Page Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/PageInputData")
 *  )
 *
 * @OA\Schema(schema="PageSortsOn", type="array",
 *     @OA\Items(type="string", enum={"name", "page_code", "group", "html_content", "priority", "sub_id"})
 * )
 */

/**
 * Class Page.
 *
 * @package namespace App\Models\Admin;
 */
class Page extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;
    use HasSlug;
    const DEFAULT_LIST_ORDER = 'desc';
    const SEARCH_FIELDS = [
        'name',
        'page_code',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'group',
        'html_content',
        'priority',
        'sub_id',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('page_code');
    }
}
