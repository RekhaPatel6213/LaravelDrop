<?php

namespace App\Models\Admin\Dispensary;

use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\CausesActivity;
use Waverfid\User\Request\UserRequest;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Events\Admin\Dispensary\UserResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Territory\Territory;

/**
 * @OA\RequestBody(
 *     request="UserForgotPassword",
 *     description="User ForgotPassword object",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/UserForgotPasswordSchema")
 * )
 * @OA\Schema(
 *     schema="UserForgotPasswordSchema",
 *     @OA\Property(property="email",type="string",format="string",description="Email", example="test@gmail.com")
 * )
 *
 * @OA\RequestBody(
 *     request="UserResetPassword",
 *     description="User ResetPassword object",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/UserResetPasswordSchema")
 * )
 * @OA\Schema(
 *     schema="UserResetPasswordSchema",
 *     required={"dispensaryId","password","password_confirmation","resetCode"},
 *     @OA\Property(property="email",type="string",format="string",description="Email", example="test@gmail.com"),
 *     @OA\Property(property="password",type="string",format="string",description="Password", example="password"),
 *     @OA\Property(property="password_confirmation",type="string",format="string",description="Confirm Password", example="password"),
 *     @OA\Property(property="token",type="string",format="string",description="Token"),
 * )
 *
 * @OA\Schema(
 *   schema="HubDispensaryUserList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/HubDispensaryUser")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 * )
 *
 * @OA\Schema(
 *   schema="HubDispensaryUser",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/HubDispensaryUserInputData"),
 *      @OA\Schema(
 *          @OA\Property(property="id",type="integer", format="int32", description="ID",example="1")
 *      )
 *   }
 * )
 *
 * @OA\Schema(
 *  schema="HubDispensaryUserPatchData",
 *      @OA\Property(property="status", type="string", description="Dispensary user status", example="ACTIVE", enum={"ACTIVE","INACTIVE"}),
 * )
 *
 * @OA\Schema(schema="HubDispensaryUserRoleType", type="array",
 *     @OA\Items(type="enum", enum={"HUB", "DISPATCH"})
 * )
 *
 * @OA\Schema(
 *   schema="HubDispensaryUserData",
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/HubDispensaryUser"))
 * )
 *
 * @OA\Schema(schema="HubDispensaryUserInputData",
 *      @OA\Property(property="first_name", type="string", description="First name", example="John"),
 *      @OA\Property(property="last_name", type="string", description="Last name", example="Doe"),
 *      @OA\Property(property="email", type="string", description="Email", example="dispensaryuser@yourmail.com"),
 *      @OA\Property(property="phone", type="string", description="Phone Number", example="9876543210"),
 *      @OA\Property(property="staff_role", type="string", description="Staff Role", example="ADMIN"),
 *      @OA\Property(property="territory_ids", type="array",  @OA\Items(type="integer", example="1")),
 *      @OA\Property(property="role", type="string", description="Role Type", example="ALL", enum={"ALL", "HUB", "DISPATCH"}),
 *      @OA\Property(property="permissions", type="array", description="Role Wise Permission", @OA\Items(type="integer", example="1")),
 *  )
 *
 * @OA\RequestBody(
 *     request="HubDispensaryUserRequest",
 *     description="DispensaryUser Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/HubDispensaryUserInputData")
 *  )
 *
 * @OA\Schema(schema="HubDispensaryUserSortsOn", type="array",
 *     @OA\Items(type="string", enum={"full_name", "email", "last_activity", "role", "status"})
 * )
 *
 * @OA\Schema(
 *   schema="PermissionList",
 *   required={"data"},
 *   @OA\Property(property="data", type="object",
 *      @OA\Property(property="type", type="object",
 *          @OA\Property(property="id", type="integer", description="Permission Name", example="Permission Name")
 *      )
 *   )
 * )
 */

class DispensaryUser extends Authenticatable implements JWTSubject, Transformable
{
    use HasFactory, Notifiable, CausesActivity, TransformableTrait, SoftDeletes, DispensaryTrait;
    use HasRoles;

    protected $fillable = [
        'dispensary_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'staff_role',
        'role',
        'territory_ids',
        'status'
    ];

    protected $casts = [
        'territory_ids' => 'array',
    ];

    public const YES = 'YES';
    public const NO = 'NO';
    public const ALL = 'ALL';
    public const HUB = 'HUB';
    public const DISPATCH = 'DISPATCH';
    public const ACTIVE = 'ACTIVE';
    public const INACTIVE = 'INACTIVE';
    const DEFAULT_LIST_ORDER = 'desc';

    const SEARCH_FIELDS = [
        'first_name', 'last_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    protected $appends = [
        'full_name'
    ];

    public function getFullNameAttribute()
    {
        return trim(ucfirst($this->first_name).' '.ucfirst($this->last_name));
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getPasswordToken()
    {
        return app('auth.password')->broker('dispensary_users')->createToken($this);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        event(new UserResetPassword($this, $token));
    }

    public function territoryModules()
    {
        return $this->morphToMany(Territory::class, 'module','territory_modules');
    }
}
