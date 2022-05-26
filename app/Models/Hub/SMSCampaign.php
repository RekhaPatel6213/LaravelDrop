<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Http\Traits\DispensaryTrait;

/**
 * @OA\Schema(
 *   schema="SMSCampaignList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/SMSCampaign")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 *  )
 * @OA\Schema(
 *   schema="SMSCampaign",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/SMSCampaignInputData"),
 *      @OA\Schema(
 *          @OA\Property(property="id",type="integer",format="int32",description="ID", example="1")
 *      )
 *   }
 *  )
 *
 * @OA\Schema(
 *   schema="SMSCampaignData",
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/SMSCampaign"))
 * )
 *
 * @OA\Schema(schema="SMSCampaignInputData",
 *     @OA\Property(property="patient_type", type="string", description="Name", example="BOTH", enum={"BOTH", "MEDICAL", "RECREATIONAL"}),
 *     @OA\Property(property="segmentation", type="integer" ,format="int32", description="SMSCampaign Segmentation", example="1"),
 *     @OA\Property(property="territory_ids", type="array", @OA\Items(type="integer", example="1")),
 *     @OA\Property(property="message", type="string", description="SMSCampaign Message", example="SMSCampaign Message"),
 *     @OA\Property(property="type_scheduled", type="string", description="Schedule Type", example="SEND-NOW", enum={"SEND-NOW", "SEND-LATER"}),
 *     @OA\Property(property="total_customer", type="string", description="Total Customer", example="1"),
 *     @OA\Property(property="schedule_date", type="string", description="Schedule Date", example=""),
 *     @OA\Property(property="schedule_time", type="array", @OA\Items(type="integer", example="")),
 *  )
 *
 * @OA\RequestBody(
 *     request="SMSCampaignRequest",
 *     description="SMSCampaign Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/SMSCampaignInputData")
 *  )
 *
 * @OA\Schema(schema="SMSCampaignSortsOn", type="array",
 *     @OA\Items(type="string", enum={"id", "created_at", "schedule_date", "message", "total_send", "status"})
 * )
 *
 *  @OA\Schema(
 *   schema="TotalCustomer",
 *   required={"data"},
 *   @OA\Property(property="data", type="array",
 *     @OA\Items(type="integer", description="Total Customer Count", example="1")
 *   )
 * )

 */

/**
 * Class SMSCampaign.
 *
 * @package namespace App\Models\Hub;
 */
class SMSCampaign extends Model implements Transformable
{
    use TransformableTrait, DispensaryTrait;

    protected $table = 'sms_campaigns';

    public const STATUS = ['PENDING' => 'PENDING', 'INPROGRESS' => 'IN-PROGRESS', 'SENT' => 'SENT', 'CANCEL' => 'CANCEL', 'FAIL' => 'FAIL'];
    const TYPE_SCHEDULE = ["SEND_NOW" => "SEND-NOW", "SEND_LATER" => "SEND-LATER"];
    const DEFAULT_LIST_ORDER = 'desc';
    const SEARCH_FIELDS = ['id','message'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'added_by',
        'customer_type',
        'segmentation',
        'territory_ids',
        'message',
        'total_send',
        'total_customer',
        'type_scheduled',
        'schedule_time',
        'schedule_date'
    ];

    protected $casts = [
        'schedule_time' => 'array',
        'territory_ids' => 'array'
    ];

}
