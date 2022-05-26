<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Traits\DispensaryTrait;

/**
 * @OA\Schema(
 *   schema="VendorList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Vendor")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 *  )
 * @OA\Schema(
 *   schema="Vendor",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/VendorInputData"),
 *      @OA\Schema(
 *          @OA\Property(property="id",type="integer",format="int32",description="ID", example="1")
 *      ),
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp"),
 *   }
 *  )
 *
 * @OA\Schema(schema="VendorInputData",
 *      @OA\Property(property="name", type="string", description="Name", example="vendor"),
 *     *@OA\Property(property="licence", type="string", description="Licence Number", example="12345678"),
 *      @OA\Property(property="email", type="string", description="Email", example="vendor@yourmail.com"),
 *      @OA\Property(property="phone", type="string", description="", example="123456789"),
 *  )
 *
 * @OA\RequestBody(
 *     request="VendorRequest",
 *     description="Vendor Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/VendorInputData")
 *  )
 *
 * @OA\Schema(schema="VendorSortsOn", type="array",
 *     @OA\Items(type="string", enum={"name", "licence", "email", "phone"})
 * )
 */

/**
 * Class Vendors.
 *
 * @package namespace App\Models\Hub;
 */
class Vendor extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes, DispensaryTrait;
    const DEFAULT_LIST_ORDER = 'desc';
    const SEARCH_FIELDS = [
        'name',
        'email',
        'licence',
        'phone'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dispensary_id',
        'name',
        'email',
        'licence',
        'phone'
    ];
}
