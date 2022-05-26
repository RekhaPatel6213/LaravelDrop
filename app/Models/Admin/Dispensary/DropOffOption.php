<?php

namespace App\Models\Admin\Dispensary;

use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * Class DropOffOption
 * @package App\Models\Admin\Dispensary
 *
 * @OA\Schema(
 *   schema="DropOffOptionsListRes",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/DropOffOptionsListAll")),
 *  )
 *
 * @OA\Schema(
 *   schema="DropOffOptionsListAllRes",
 *   required={"data"},
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/DropOffOptionsListAll"),
 *  )
 *
 *@OA\Schema(
 *   schema="DropOffOptionsListAll",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/DropOffOptionsList"),
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp")
 *   }
 *  )
 *
 * @OA\Schema(schema="DropOffOptions",
 *      @OA\Property(property="title", type="string", description="Title", example="In the Hotel"),
 *  )
 *
 * @OA\Schema(schema="DropOffOptionsList",
 *      @OA\Property(property="id", type="integer", description="Id", example="1"),
 *      @OA\Property(property="title", type="string", description="Title", example="In the Hotel"),
 *      @OA\Property(property="slug", type="string", description="slug", example="in-the-hotel"),
 *      @OA\Property(property="status", type="string", description="status", example="ACTIVE"),
 *  )
 *
 */
class DropOffOption extends Model implements Transformable
{
    use DispensaryTrait, TransformableTrait, HasSlug, SoftDeletes;

    protected $fillable = [
        'dispensary_id',
        'slug',
        'title',
        'status',
        ];

    /*
     * DROP_OFF_OPTIONS
     * Defines the default drop off options to be added to database
     * */
    public const DROP_OFF_OPTIONS = [
        'meet-outside' => [
            'slug' => 'meet-outside',
            'title' => 'Meet me outside',
            'default_status' => 'ACTIVE',
        ],
        'at-door' => [
            'slug' => 'at-door',
            'title' => 'Leave at door',
            'default_status' => 'ACTIVE',
        ],

    ];


    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }
}
