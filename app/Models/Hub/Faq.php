<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Traits\DispensaryTrait;

/**
 * @OA\Schema(
 *   schema="HubFaqList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/HubFaq")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 *  )
 * @OA\Schema(
 *   schema="HubFaq",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/HubFaqInputData"),
 *      @OA\Schema(
 *          @OA\Property(property="id",type="integer", format="int32", description="ID",example="1")
 *      ),
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp"),
 *   }
 *  )
 *
 * @OA\Schema(schema="HubFaqInputData",
 *      @OA\Property(property="question", type="string", description="Question", example="Question Test"),
 *      @OA\Property(property="answer", type="string", description="Answer", example="Answer Test"),
 *      @OA\Property(property="priority", type="integer", description="Set Question Priority", example="1"),
 *     @OA\Property(property="status", type="string", description="Faq Status", example="ACTIVE",  enum={"ACTIVE", "INACTIVE"}),
 *  )
 *
 * @OA\RequestBody(
 *     request="HubFaqRequest",
 *     description="Faq Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/HubFaqInputData")
 *  )
 *
 * @OA\Schema(schema="HubFaqSortsOn", type="array",
 *     @OA\Items(type="string", enum={"question", "answer", "priority", "status"})
 * )
 */


/**
 * Class Faq.
 *
 * @package namespace App\Models\Hub;
 */
class Faq extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes, DispensaryTrait;

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
        'dispensary_id',
        'question',
        'answer',
        'priority',
        'status'
    ];
}
