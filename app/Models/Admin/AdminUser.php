<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\CausesActivity;
use Waverfid\User\Request\UserRequest;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use App\Events\Admin\ResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * @OA\RequestBody(
 *     request="ForgotPassword",
 *     description="ForgotPassword object",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/ForgotPasswordSchema")
 * )
 * @OA\Schema(
 *     schema="ForgotPasswordSchema",
 *     @OA\Property(property="email",type="string",format="string",description="Email", example="test@gmail.com")
 * )
 *
 * @OA\RequestBody(
 *     request="ResetPassword",
 *     description="ResetPassword object",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/ResetPasswordSchema")
 * )
 * @OA\Schema(
 *     schema="ResetPasswordSchema",
 *     required={"userId","password","password_confirmation","resetCode"},
 *     @OA\Property(property="email",type="string",format="string",description="Email", example="test@gmail.com"),
 *     @OA\Property(property="password",type="string",format="string",description="Password", example="password"),
 *     @OA\Property(property="password_confirmation",type="string",format="string",description="Confirm Password", example="password"),
 *     @OA\Property(property="token",type="string",format="string",description="Token"),
 * )
 *
 * @OA\Schema(
 *   schema="AdminList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminUser")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 * )
 *
 * @OA\Schema(
 *   schema="AdminUser",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/AdminInputData"),
 *      @OA\Schema(
 *          @OA\Property(property="id",type="integer",format="int32",description="ID", example="1"),
 *          @OA\Property(property="full_name", type="string", description="Full Name", example="John Doe"),
 *          @OA\Property(property="status",type="string",description="status", example="ACTIVE"),
 *          @OA\Property(property="last_login",type="string",description="last login", example="2021-10-22 02:12:09")
 *      ),
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp"),
 *   }
 *  )
 *
 * @OA\Schema(schema="AdminInputData",
 *      @OA\Property(property="first_name", type="string", description="First Name", example="John"),
 *      @OA\Property(property="last_name", type="string", description="Last Name", example="Doe"),
 *      @OA\Property(property="email", type="string", description="Email Address", example="johndoe@gmail.com"),
 *      @OA\Property(property="phone_number", type="string", description="Phone Number", example="1234567890"),
 *      @OA\Property(property="role", type="string", description="Role", example="SUPER_ADMIN", enum={"SUPER_ADMIN", "ACCOUNT_MANAGER", "PROMOTIONS", "SALES", "PROMOTION_MANAGER", "GENERATED_ADMIN"}),
 *      @OA\Property(property="status",type="string", description="status", example="ACTIVE"),
 *  )
 *
 * @OA\RequestBody(
 *     request="AdminUserRequest",
 *     description="Admin User Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/AdminInputData")
 *  )
 *
 * @OA\RequestBody(
 *     request="AdminUserStatusRequest",
 *     description="Admin User Status update Request body",
 *     required=true,
 *     @OA\JsonContent(
 *          @OA\Property(property="status", type="string", description="Status", example="ACTIVE")
 *     )
 *  )
 *
 * @OA\Schema(schema="AdminSortsOn", type="array",
 *     @OA\Items(type="string", enum={"first_name", "last_name", "email", "phone_number", "role", "last_login", "status"})
 * )
 *
 * @OA\Schema(
 *   schema="AdminDetail",
 *   @OA\Property(property="data", type="object",
 *      allOf={
 *          @OA\Schema(ref="#/components/schemas/AdminUser"),
 *          @OA\Schema(
 *              @OA\Property(property="last_login", type="string", description="User Last Login", example="2021-10-22 02:12:09"),
 *          )
 *      }
 *   )
 * )
 */
class AdminUser extends Authenticatable implements JWTSubject
{
    use HasFactory, SoftDeletes, Notifiable, CausesActivity;

    public const ACTIVE = 'ACTIVE';
    public const INACTIVE = 'INACTIVE';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'role',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $dates = [
        'last_login'
    ];

    protected $appends = [
        'full_name'
    ];

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

    public function getFullNameAttribute()
    {
        return trim(ucfirst($this->first_name).' '.ucfirst($this->last_name));
    }

    public function getPasswordToken()
    {
        return app('auth.password')->broker('admin_users')->createToken($this);
    }

    public function getRole()
    {
        return $this->belongsTo(\App\Models\Role::class, 'name', 'role');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        event(new ResetPassword($this, $token));
    }
}
