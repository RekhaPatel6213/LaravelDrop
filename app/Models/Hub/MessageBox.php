<?php

namespace App\Models\Hub;

use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class MessageBox.
 *
 * @package namespace App\Models\Hub;
 *
 * @OA\Schema(
 *   schema="HomeMessagesRes",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/HomeMessagesList"))
 * )
 *
 * @OA\Schema(schema="MessageBoxInputData",
 *      @OA\Property(property="title", type="string", description="Title", example="New Year Offer"),
 *      @OA\Property(property="description", type="string", description="Description", example="Exiting new year offers available"),
 * )
 *
 * @OA\Schema(schema="MessageBoxReorder",
 *      @OA\Property(property="message_ids", type="array", @OA\Items(type="integer", example="1"))
 * )
 *  @OA\Schema(schema="HomeMessagesList",
 *      @OA\Property(property="id", type="integer", description="message id", example="1"),
 *      @OA\Property(property="dispensary_id", type="integer", description="dispensary id", example="1"),
 *      @OA\Property(property="title", type="string", description="title", example="New year Offer"),
 *      @OA\Property(property="description", type="string", description="message description", example="Exiting new year offers available"),
 *      @OA\Property(property="position", type="string", description="message order", example="1"),
 *      @OA\Property(property="added_by", type="string", description="message added by", example="1"),
 *  )
 *
 */
class MessageBox extends Model implements Transformable
{
    use TransformableTrait, DispensaryTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dispensary_id',
        'title',
        'description',
        'position',
        'added_by',
    ];

}
