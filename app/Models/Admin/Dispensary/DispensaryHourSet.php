<?php

namespace App\Models\Admin\Dispensary;

use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class DispensaryHourSet
 * @package App\Models\Admin\Dispensary
 *
 *
 * @OA\RequestBody(
 *     request="HubDispensaryTimingsAddUpdateRequest",
 *     description="Hub >> Delivery Settings >> Shop Information",
 *     required=true,
 *     @OA\JsonContent(
 *          @OA\Property(property="data", type="object", ref="#/components/schemas/SingleHourSetSchema")
 *     )
 *  )
 *
 * @OA\RequestBody(
 *     request="HubDispensaryPhoneNumbersRequest",
 *     description="Hub >> Delivery Settings >> Dispensary phone numbers, Phone Number as KEY, Territory IDs as Values",
 *     required=true,
 *     @OA\JsonContent(
 *          @OA\Property(property="data", type="object", ref="#/components/schemas/SinglePhoneNumbersSchema")
 *     )
 *  )
 *
 * @OA\Schema(
 *     schema="SingleHourSetSchema",
 *     @OA\Property(property="1014", type="object", ref="#/components/schemas/HourSetSchema" ),
 *     @OA\Property(property="0", type="object", ref="#/components/schemas/HourSetSchema" ),
 * )
 *
 *  @OA\Schema(
 *     schema="HourSetSchema",
 *     @OA\Property(property="name", type="string", description="Name of terretories", example="Common Area"),
 *     @OA\Property(property="territories", type="array", description="Territory Ids", @OA\Items(type="integer", example="1")),
 *     @OA\Property(property="timings", type="object", ref="#/components/schemas/TimingsSchema" )
 * )
 *
 *  @OA\Schema(
 *     schema="TimingsSchema",
 *     @OA\Property(property="sun",type="object",description="sun", ref="#/components/schemas/FromToSchema"),
 *     @OA\Property(property="mon",type="string",description="mon", ref="#/components/schemas/FromToSchema"),
 *     @OA\Property(property="tue",type="string",description="tue", ref="#/components/schemas/FromToSchema"),
 *     @OA\Property(property="wed",type="string",description="wed", ref="#/components/schemas/FromToSchema"),
 *     @OA\Property(property="thu",type="string",description="thu", ref="#/components/schemas/FromToSchema"),
 *     @OA\Property(property="fri",type="string",description="fri", ref="#/components/schemas/FromToSchema"),
 *     @OA\Property(property="sat",type="string",description="sat", ref="#/components/schemas/FromToSchema")
 * )
 *
 * @OA\Schema(
 *     schema="FromToSchema",
 *     @OA\Property(property="from_time", type="string", description="From", example="10:00:00"),
 *     @OA\Property(property="to_time", type="string", description="To", example="10:00:00")
 * )
 *
 */

class DispensaryHourSet extends Model implements Transformable
{
    use DispensaryTrait, TransformableTrait;

    protected $fillable = [
        'dispensary_id',
        'name',
        ];

    public function dispensaryTiming()
    {
        return $this->hasMany(DispensaryTiming::class);
    }
}
