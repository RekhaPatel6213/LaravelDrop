<?php

namespace App\Models\Admin\Dispensary;

use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 *
 *  @OA\Schema(
 *   schema="ProgramList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array" , @OA\Items(ref="#/components/schemas/ProgramListData")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 *  )
 *
 * @OA\Schema(
 *   schema="ProgramInputDataRes",
 *   required={"data"},
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/ProgramInputData")
 *  )
 *
 * @OA\Schema(
 *   schema="LoyaltyDefaultsRes",
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/LoyaltyDefaultsData")
 *  )
 *
 * @OA\Schema(
 *   schema="LoyaltyDefaultsData",
 *   @OA\Property(property="STANDARD LOYALTY", type="object", ref="#/components/schemas/LoyaltyDefaultsStandardData")
 *  )
 *
 *  @OA\Schema(
 *   schema="Program",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp")
 *   }
 *  )
 *
 *  @OA\Schema(schema="ProgramSortsOn", type="array",
 *     @OA\Items(type="string", enum={"name"})
 * )
 *
 *
 * @OA\Schema(schema="ProgramInputData",
 *      @OA\Property(property="name", type="string", description="Program name", example="Birthday Bash"),
 *      @OA\Property(property="points", type="integer", description="Points awarded", example="100"),
 *      @OA\Property(property="start_time", type="string", format="date-time", description="Start time", example="12:00"),
 *      @OA\Property(property="end_time", type="string", format="date-time", description="End time", example="06:00"),
 *      @OA\Property(property="status", type="string", description="Status", example="ACTIVE"),
 *      @OA\Property(property="active_days", type="string", description="Days active", example="1,2,3"),
 *      @OA\Property(property="schedule", type="string", description="Program schedule", example="WEEKLY"),
 *      @OA\Property(property="custom_message", type="string", description="Custom SMS", example="Claim an extra 100 points from the Birthday Bash if you order from 12:00am - 12:30am on Sunday.")
 *  )
 *
 * @OA\Schema(schema="ProgramListData",
 *      @OA\Property(property="id", type="integer", description="Program id", example="1"),
 *      @OA\Property(property="name", type="string", description="Program name", example="Birthday Bash"),
 *      @OA\Property(property="points_text", type="string", description="Points awarded", example="1 Point for every $1 spent"),
 *      @OA\Property(property="type", type="string", description="Deal Type", example="STANDARD LOYALTY"),
 *      @OA\Property(property="status", type="string", description="Status", example="ACTIVE"),
 *  )
 *
 * @OA\Schema(schema="LoyaltyDefaultsStandardData",
 *      @OA\Property(property="id", type="integer", description="Program id", example="1"),
 *      @OA\Property(property="name", type="string", description="Program name", example="Standard Loyalty"),
 *      @OA\Property(property="type", type="string", description="Deal Type", example="STANDARD LOYALTY"),
 *      @OA\Property(property="status", type="string", description="Status", example="ACTIVE"),
 *  )
 *
 *  @OA\Schema(schema="ProgramPatchData",
 *      @OA\Property(property="status", type="string", description="status", example="DISABLED")
 *   )
 *
 * @OA\RequestBody(
 *     request="ProgramRequest",
 *     description="Program Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/ProgramInputData")
 *  )
 *
 * @OA\Schema(schema="LoyaltyDefaults",
 *      @OA\Property(
 *     property="defaults",
 *     type="array",
 *     example={
 *     {"id":5,"points":1,"status":"DISABLED"},
 *     {"id":6,"points":100,"status":"ACTIVE"},
 *     {"id":7,"points":100,"status":"ACTIVE"},
 *     {"id":8,"points":100,"status":"ACTIVE"},
 *     },
 *      @OA\Items(
 *                      @OA\Property(
 *                         property="id",
 *                         type="integer",
 *                         example=""
 *                      ),
 *                      @OA\Property(
 *                         property="points",
 *                         type="integer",
 *                         example=""
 *                      ),@OA\Property(
 *                         property="status",
 *                         type="string",
 *                         example=""
 *                      )
 *     ),
 *  ),
 * )
 *
 *
 */

class LoyaltyProgram extends Model implements Transformable
{
    use DispensaryTrait, TransformableTrait, SoftDeletes;

    public const DEFAULT_PROGRAMS = [
        'standard' => [
            'name' => 'Standard Loyalty',
            'type' => self::STANDARD_LOYALTY,
            'default_status' => self::ACTIVE,
            'points' => 1,
        ],

        'new_loyalty' => [
            'name' => 'New Loyalty Customer',
            'type' => self::NEW_LOYALTY,
            'default_status' => self::ACTIVE,
            'points' => 100,
        ],

        'birthday' => [
            'name' => 'Customer Birthday',
            'type' => self::BIRTHDAY,
            'default_status' => self::ACTIVE,
            'points' => 100,
        ],

        'referral' => [
            'name' => 'Referral Program',
            'type' => self::REFERRAL,
            'default_status' => self::ACTIVE,
            'points' => 100,
        ],

    ];

    const STANDARD_LOYALTY = 'STANDARD LOYALTY';
    const NEW_LOYALTY = 'NEW LOYALTY CUSTOMER';
    const BIRTHDAY = 'CUSTOMER BIRTHDAY';
    const REFERRAL = 'REFERRAL PROGRAM';
    const TIME_BASED = 'TIME BASED';
    const WEEKLY = 'WEEKLY';
    const BI_WEEKLY = 'BI-WEEKLY';
    const MONTHLY = 'MONTHLY';
    const MANUALLY = 'MANUALLY';
    const ACTIVE = 'ACTIVE';
    const DISABLED = 'DISABLED';
    const SMS = 'SMS';
    const DEFAULT_LIST_ORDER = 'asc';


    protected $fillable = [
        'dispensary_id',
        'name',
        'status',
        'points',
        'is_default',
        'start_time',
        'end_time',
        'type',
        'schedule',
        'active_days',
        'custom_message',
    ];

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
