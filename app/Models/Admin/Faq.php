<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *   schema="FaqList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Faq")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 *  )
 * @OA\Schema(
 *   schema="Faq",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/FaqInputData"),
 *      @OA\Schema(
 *          @OA\Property(property="id",type="integer",format="int32",description="ID", example="1")
 *      ),
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp"),
 *   }
 *  )
 *
 * @OA\Schema(schema="FaqInputData",
 *      @OA\Property(property="question", type="string", description="Question", example="Question Test"),
 *      @OA\Property(property="answer", type="string", description="Answer", example="Answer Test"),
 *      @OA\Property(property="priority", type="integer", description="Set Question Priority", example="1"),
 *     @OA\Property(property="status", type="string", description="ACTIVE, INACTIVE", example="ACTIVE"),
 *  )
 *
 * @OA\RequestBody(
 *     request="FaqRequest",
 *     description="Faq Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/FaqInputData")
 *  )
 *
 * @OA\RequestBody(
 *     request="FaqStatusRequest",
 *     description="Faq Status update Request body",
 *     required=true,
 *     @OA\JsonContent(
 *          @OA\Property(property="status", type="string", description="Status", example="ACTIVE")
 *     )
 *  )
 *
 * @OA\Schema(schema="FaqSortsOn", type="array",
 *     @OA\Items(type="string", enum={"question", "answer", "priority", "status"})
 * )
 */

/**
 * Class Faq.
 *
 * @package namespace App\Models\Admin;
 */
class Faq extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    public const ACTIVE = 'ACTIVE';
    public const INACTIVE = 'INACTIVE';

    const DEFAULT_LIST_ORDER = 'desc';
    const SEARCH_FIELDS = [
        'question'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question',
        'answer',
        'priority',
        'status'
    ];
}
