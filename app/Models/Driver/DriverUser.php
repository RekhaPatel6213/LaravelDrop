<?php

namespace App\Models\Driver;

use App\Http\Traits\DispensaryTrait;
use App\Http\Traits\UserTrait;
use App\Models\Territory\Territory;
use App\Models\Territory\TerritoryModule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 *  @OA\Schema(
 *   schema="DriverUserList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/DriverUserListRes")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 *  )
 *
 *
 *  @OA\Schema(
 *   schema="DriverUser",
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/DriverUserData")
 *  )
 *
 * @OA\Schema(
 *   schema="DriverUserData",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/DriverUserListRes"),
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp")
 *   }
 *  )
 *
 *  @OA\Schema(schema="DriverUserSortsOn", type="array",
 *     @OA\Items(type="string", enum={"id", "name", "email", "phone","device_version"})
 * )
 *
 *
 * @OA\Schema(schema="DriverUserInputData",
 *      @OA\Property(property="profile_image", type="file", description="DriverUser profile image"),
 *      @OA\Property(property="first_name", type="string", description="First Name", example="John"),
 *      @OA\Property(property="last_name", type="string", description="Last Name", example="Doe"),
 *      @OA\Property(property="email", type="string", description="Email", example="johndoe@yopmail.com"),
 *      @OA\Property(property="phone", type="string", description="Phone Number", example="9876543210"),
 *      @OA\Property(property="vehicle_type", type="string", enum={"TRUCK", "CAR", "MOTORCYCLE", "BIKE", "WALK"}, description="Vehicle Type"),
 *      @OA\Property(property="vehicle_model", type="string", description="Vehicle Model(Year,Model)", example="2008 mk43"),
 *      @OA\Property(property="vehicle_color", type="string", description="Vehicle Color", example="Blue and Red"),
 *      @OA\Property(property="vehicle_licence", type="string", description="Licence Plate", example="US 29 My 1970"),
 *     @OA\Property(property="territory_names", type="string", description="Territory names", example="Riverside,Riverside2"),
 *      @OA\Property(property="inventory_name", type="string", description="Inventory name", example="Inv Tony"),
 *  )
 *
 * @OA\Schema(schema="DriverUserListRes",
 *      @OA\Property(property="id", type="integer", description="DriverUser id", example="1"),
 *      @OA\Property(property="profile_image", type="string", description="DriverUser profile image", example="https://example.com/image.png"),
 *      @OA\Property(property="name", type="string", description="First Name", example="John Doe"),
 *      @OA\Property(property="phone", type="string", description="Phone Number", example="9876543210"),
 *      @OA\Property(property="device_type", type="string", description="Device Type", example="Android"),
 *      @OA\Property(property="device_version", type="string", description="Device Version", example="13.1.2"),
 *      @OA\Property(property="territory_names", type="string", description="Territory names", example="Riverside,Riverside2"),
 *      @OA\Property(property="inventory_name", type="string", description="Inventory name", example="Inv Tony"),
 *      @OA\Property(property="status", type="string", description="Status", example="OFFLINE"),
 *  )
 *
 * @OA\Schema(schema="DriverUserInputDataPatch",
 *      @OA\Property(property="first_name", type="string", description="First Name", example="John"),
 *      @OA\Property(property="last_name", type="string", description="Last Name", example="Doe"),
 *      @OA\Property(property="email", type="string", description="Email", example="johndoe@yopmail.com"),
 *      @OA\Property(property="phone", type="string", description="Phone Number", example="9876543210"),
 *      @OA\Property(property="vehicle_type", type="string", enum={"TRUCK", "CAR", "MOTORCYCLE", "BIKE", "WALK"}, description="Vehicle Type"),
 *      @OA\Property(property="vehicle_model", type="string", description="Vehicle Model(Year,Model)", example="2008 mk43"),
 *      @OA\Property(property="vehicle_color", type="string", description="Vehicle Color", example="Blue and Red"),
 *      @OA\Property(property="vehicle_licence", type="string", description="Licence Plate", example="US 29 My 1970"),
 *  )
 *
 * @OA\RequestBody(
 *     request="DriverRequest",
 *     description="DriverUser Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/DriverUserInputData")
 *  )
 *
 */


/**
 * Class DriverUser.
 *
 * @package namespace App\Models\DriverUser;
 */
class DriverUser extends Model implements Transformable, HasMedia
{
    use TransformableTrait, DispensaryTrait, UserTrait, InteractsWithMedia, SoftDeletes;

    /*
     * DEFAULT_LIST_ORDER
     * Default listing order of Drivers
     * */
    const DEFAULT_LIST_ORDER = 'desc';

    /*
     * SEARCH_FIELDS
     * is used for columns on which search can be applicable
     * */
    const SEARCH_FIELDS = ['id', 'first_name', 'last_name', 'email', 'phone'];


    const OFFLINE = 'OFFLINE';
    const ONLINE = 'ONLINE';
    const IDLE = 'IDLE';
    const TRUCK = 'TRUCK';
    const CAR = 'CAR';
    const MOTORCYCLE = 'MOTORCYCLE';
    const BIKE = 'BIKE';
    const WALK = 'WALK';
    const ANDROID = 'ANDROID';
    const IOS = 'IOS';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dispensary_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'vehicle_type',
        'vehicle_model',
        'vehicle_color',
        'vehicle_licence',
        'status',
    ];

    protected $appends = [
        'name'
    ];

    public function getNameAttribute()
    {
        return trim(ucfirst($this->first_name).' '.ucfirst($this->last_name));
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_images')
            ->useDisk('DO')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(50)
            ->height(50);
    }

    public function territoryModules()
    {
        return $this->morphToMany(Territory::class, 'module','territory_modules');
    }
}
