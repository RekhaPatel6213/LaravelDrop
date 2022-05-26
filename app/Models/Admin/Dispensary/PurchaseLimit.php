<?php

namespace App\Models\Admin\Dispensary;

use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * @OA\Schema(
 *   schema="PurchaseLimitList",
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PurchaseLimitUpdateDataRes")),
 *  )
 *
 * @OA\Schema(
 *   schema="PurchaseLimitUpdateDataRes",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/PurchaseLimitUpdateExtra"),
 *     @OA\Schema(ref="#/components/schemas/PurchaseLimitUpdateData"),
 *   }
 *  )
 *
 * @OA\RequestBody(
 *     request="HubDispensaryPurchaseLimitUpdateRequest",
 *     description="Dispensary purchase limit update",
 *     required=true,
 *     @OA\JsonContent(
 *          @OA\Schema(ref="#/components/schemas/PurchaseLimitUpdateData")
 *     )
 *  )
 *
 * @OA\Schema(
 *   schema="PurchaseLimitUpdateExtra",
 *   @OA\Property(property="id", type="integer", description="Id", example="1"),
 *  )
 *
 *  @OA\Schema(
 *   schema="PurchaseLimitUpdateData",
 *          @OA\Property(property="state", type="string", description="State", example="KY"),
 *          @OA\Property(property="flower_rec_limit", type="string", description="Flower Adult Use Limits G", example="100"),
 *          @OA\Property(property="flower_med_limit", type="string", description="Flower Medical Use Limits G", example="100"),
 *          @OA\Property(property="con_rec_limit", type="string", description="Concentrate Adult Use Limits G", example="100"),
 *          @OA\Property(property="con_med_limit", type="string", description="Concentrate Medical Use Limits G", example="100"),
 *          @OA\Property(property="plant_rec_limit", type="string", description="Plant Adult Use Limits G", example="100"),
 *          @OA\Property(property="plant_med_limit", type="string", description="Plant Medical Use Limits G", example="100")
 *  )
 *
 *
 * Class PurchaseLimit.
 *
 * @package namespace App\Models\Admin\Dispensary;
 */
class PurchaseLimit extends Model implements Transformable
{
    use TransformableTrait, DispensaryTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'state',
        'dispensary_id',
        'flower_rec_limit',
        'flower_med_limit',
        'con_rec_limit',
        'con_med_limit',
        'plant_rec_limit',
        'plant_med_limit',
    ];
}
