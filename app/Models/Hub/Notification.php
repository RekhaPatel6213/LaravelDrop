<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *   schema="NotificationList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Notification")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 *  )
 * @OA\Schema(
 *   schema="Notification",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/NotificationInputData"),
 *      @OA\Schema(
 *          @OA\Property(property="id",type="integer",format="int32",description="ID", example="1")
 *      ),
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp"),
 *   }
 *  )
 *
 * @OA\Schema(schema="NotificationInputData",
 *     @OA\Property(property="title", type="string", description="Title", example="Notification Title"),
 *     @OA\Property(property="message", type="string", description="Message", example="Message"),
 *  )
 *
 * @OA\RequestBody(
 *     request="NotificationRequest",
 *     description="Notification Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/NotificationInputData")
 *  )
 *
 * @OA\Schema(schema="NotificationSortsOn", type="array",
 *     @OA\Items(type="string", enum={"title"})
 * )
 */

/**
 * Class Notification.
 *
 * @package namespace App\Models\Hub;
 */
class Notification extends Model implements Transformable
{
    use TransformableTrait, DispensaryTrait, SoftDeletes;

    public const YES = 'YES',  NO = 'NO';
    const DEFAULT_LIST_ORDER = 'desc';
    const SEARCH_FIELDS = [
        'title'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'message',
        'added_by'
    ];

    /**
     * Get customer notification.
     */
    public function customerNotification() {
        return $this->hasMany(CustomerNotification::class, 'notification_id','id');
    }
}