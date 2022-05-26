<?php

namespace App\Models\Admin\Customer;

use App\Http\Traits\DispensaryTrait;
use App\Http\Traits\UserTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * @OA\Schema(
 *   schema="DispensaryCustomerList",
 *   required={"data"},
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 *  )
 *
 * @OA\Schema(schema="DispensaryCustomerSortsOn", type="array",
 *     @OA\Items(type="string", enum={"id", "name", "email", "phone"})
 * )
 *
 * @OA\Schema(schema="DispensaryCustomerStatus", type="array",
 *     @OA\Items(type="string", enum={"VERIFIED", "UNVERIFIED", "DECLINED"})
 * )
 */


class DispensaryCustomer extends Model implements Transformable, HasMedia
{
    use TransformableTrait, InteractsWithMedia, UserTrait, SoftDeletes, DispensaryTrait;

    const DEFAULT_STATUS = 0;

    /*
     * SEARCH_FIELDS
     * is used for columns on which search can be applicable
     * */
    const SEARCH_FIELDS = ['id', 'name'];

    /*
    * DEFAULT_LIST_STATUS
    * Customers having this status will be displayed by default in listing
    * */
    const DEFAULT_LIST_STATUS = 'ACTIVE';

    /*
     * DEFAULT_LIST_ORDER
     * Default listing order of Customers
     * */
    const DEFAULT_LIST_ORDER = 'desc';

    const ACTIVE = 'ACTIVE';
    const BLOCKED = 'BLOCKED';
    const VERIFIED = 'VERIFIED';
    const UNVERIFIED = 'UNVERIFIED';
    const DECLINED = 'DECLINED';
    const MEDICAL = 'MEDICAL';
    const RECREATIONAL = 'RECREATIONAL';


    protected $fillable = [
        'dispensary_id',
        'customer_id',
        'first_name',
        'last_name',
        'patient_type',
        'patient_number',
        'patient_doctor',
        'medical_expire_date',
        'territory_id',
        'verify_status',
        'sms_enabled',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('card')
            ->useDisk('DO')
            ->singleFile();

        $this->addMediaCollection('medical')
            ->useDisk('DO')
            ->singleFile();

        $this->addMediaCollection('other')
            ->useDisk('DO');
    }
}
