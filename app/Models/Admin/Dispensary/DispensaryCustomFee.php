<?php

namespace App\Models\Admin\Dispensary;

use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class DispensaryCustomFee.
 *
 * @package namespace App\Models\Admin\Dispensary;
 *
 * @mixin Eloquent
 *
 * @OA\Schema(schema="CustomFees",
 *      @OA\Property(property="title", type="string", description="Custom Fee Title", example="Delivery Tax"),
 *      @OA\Property(property="description", type="string", description="Custom fee description", example="Delivery Tax Description"),
 *      @OA\Property(property="fee_amount", type="integer", description="Fee Amount", example="1.5")
 *  )
 *
 * @OA\Schema(schema="CustomFeesUpdate",
 *      @OA\Property(
 *     property="data",
 *     type="array",
 *     example={
 *     {"id":1,"title":"Delivery Tax", "description":"Delivery Tax Description", "fee_amount":1.5},
 *     {"id":2,"title":"Delivery Vat", "description":"Delivery Vat Description", "fee_amount":1.5},
 *     },
 *      @OA\Items(
 *                      @OA\Property(
 *                         property="id",
 *                         type="number",
 *                         example=""
 *                      ),
 *                      @OA\Property(
 *                         property="title",
 *                         type="string",
 *                         example=""
 *                      ),@OA\Property(
 *                         property="description",
 *                         type="string",
 *                         example=""
 *                      ),@OA\Property(
 *                         property="fee_amount",
 *                         type="integer",
 *                         example=""
 *                      )
 *     ),
 *  ),
 * )
 *
 *
 */
class DispensaryCustomFee extends Model implements Transformable
{
    use TransformableTrait, DispensaryTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dispensary_id',
        'title',
        'description',
        'fee_amount',
        'total_collection',
    ];
}
