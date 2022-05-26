<?php

namespace App\Models\Admin\Dispensary;

use App\Models\Driver\DriverUser;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Billable;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 *  @OA\Schema(
 *   schema="DispensaryList",
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/DispensaryListData")),
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 *  )
 *
 * @OA\Schema(
 *   schema="DispensaryInputDataResp",
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/DispensaryInputData"),
 *  )
 *
 *  @OA\Schema(
 *   schema="HubDispensaryUpdateResp",
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/HubDispensaryUpdateRequest"),
 *  )
 *
 * @OA\Schema(
 *   schema="DispensaryNotesInputDataResp",
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/DispensaryNotesDispData"),
 *  )
 *
 *  @OA\Schema(
 *   schema="DispensaryNotesInputDataRespArr",
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/DispensaryNotesDispData")),
 *  )
 *
 *  @OA\Schema(
 *   schema="Dispensary",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp")
 *   }
 *  )
 *
 *  @OA\Schema(schema="DispensarySortsOn", type="array",
 *     @OA\Items(type="string", enum={"id", "name"})
 * )
 *
 * @OA\Schema(schema="DispensaryStatus", type="array",
 *     @OA\Items(type="string", enum={"live", "pending", "inactive"})
 * )
 *
 * @OA\Schema(schema="DispensaryNotesInputData",
 *     @OA\Property(property="dispensary_id", type="string", description="Dispensary Id", example="1"),
 *     @OA\Property(property="note", type="string", description="a note to add", example="This is a test note."),
 * )
 *
 * @OA\Schema(schema="NotesAttributes",
 *     @OA\Property(property="name", type="string", description="Dispensary names", example="Dispensary Name"),
 * )
 *
 * @OA\Schema(schema="NotesProperties",
 *     @OA\Property(property="attributes", type="object", ref="#/components/schemas/NotesAttributes"),
 * )
 *
 * @OA\Schema(schema="DispensaryNotesDispData",
 *     allOf={
 *     @OA\Schema(
 *      @OA\Property(property="id", type="integer", description="id", example="1"),
 *      @OA\Property(property="description", type="string", description="description", example="created"),
 *      @OA\Property(property="causer_id", type="integer", description="user id", example="1"),
 *      @OA\Property(property="properties", type="object", ref="#/components/schemas/NotesProperties"),
 *      ),
 *     @OA\Schema(ref="#/components/schemas/StandardTimestamp"),
 *   }
 * )
 *
 * @OA\Schema(schema="DispensaryInputData",
 *      @OA\Property(property="logo", type="file", description="Dispensary Logo"),
 *      @OA\Property(property="name", type="string", description="Dispensary name", example="Test Dispensary"),
 *      @OA\Property(property="email", type="string", description="Dispensary email", example="dispensary@yourmail.com"),
 *      @OA\Property(property="phone", type="string", description="Dispensary Phone Number", example="9876543210"),
 *      @OA\Property(property="address", type="string", description="Dispensary Address", example="Los Angeles"),
 *      @OA\Property(property="domain", type="string", description="Dispensary Domain", example="yourshop"),
 *      @OA\Property(property="first_name", type="string", description="Contact first name", example="John"),
 *      @OA\Property(property="last_name", type="string", description="Contact last name", example="Doe"),
 *      @OA\Property(property="contact_email", type="string", description="Contact email", example="johndoe@yourmail.com"),
 *      @OA\Property(property="contact_phone", type="string", description="Contact phone number", example="9876543210"),
 *      @OA\Property(property="admin_user_id", type="string", description="Account Manager Id", example="10"),
 *      @OA\Property(property="bitly_link", type="string", description="Bit.ly Link", example="https://bit.ly/yourshop"),
 *      @OA\Property(property="own_domain", type="string", description="Dispensary Website Address", example="https://www.yourshop.com"),
 *      @OA\Property(property="setup_fee", type="string", description="Setup Fee", example="1200.00"),
 *      @OA\Property(property="services", type="string", description="Services Offers", example="1,2,3"),
 *      @OA\Property(property="billing_prompt", type="string", description="Billing prompt", example="MANUALLY BILLED", enum={"MANUALLY BILLED", "CARD"}),
 *      @OA\Property(property="service_fee_enabled", type="string", description="Service fee enabled", example="ENABLED", enum={"ENABLED", "DISABLED"}),
 *      @OA\Property(property="service_fee_amount", type="integer", format="int32", description="Service fee amount", example="500"),
 *      @OA\Property(property="subscription_type", type="string", description="Dispensary Subscription Type", example="price_1JuzNvSJCzzs25BnzLd7T0pd", enum={"price_1JuzNvSJCzzs25BnzLd7T0pd"}),
 *  )
 *
 * @OA\Schema(schema="DispensaryListData",
 *      @OA\Property(property="id", type="integer", description="Dispensary Id", example="1"),
 *      @OA\Property(property="logo", type="string", description="Dispensary Logo", example=""),
 *      @OA\Property(property="name", type="string", description="Dispensary name", example="Test Dispensary"),
 *      @OA\Property(property="customers", type="string", description="customers", example=""),
 *      @OA\Property(property="service_fee_enabled", type="string", description="Service fee enabled", example="ENABLED"),
 *      @OA\Property(property="service_fee_amount", type="string", description="Service fee amount", example="500"),
 *  )
 *
 *  @OA\RequestBody(
 *     request="DispensaryRequest",
 *     description="Dispensary Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/DispensaryInputData")
 *  )
 *
 * @OA\Schema(
 *     schema="SinglePhoneNumbersSchema",
 *     @OA\Property(property="9876543210", example={1,2} ),
 *     @OA\Property(property="9876543211", example={3,4} ),
 * )
 *
 * @OA\RequestBody(
 *     request="DispensaryStatusUpdateRequest",
 *     description="Dispensary Status Update Request body",
 *     required=true,
 *     @OA\JsonContent(
 *          @OA\Property(property="status", type="string", description="Dispensary status (LIVE, PENDING, INACTIVE)", example="LIVE")
 *     )
 *  )
 *
 * @OA\Schema(schema="HubDispensaryUpdateRequest",
 *     @OA\Property(property="logo", type="file", description="Dispensary Logo"),
 *     @OA\Property(property="header_logo", type="file", description="Dispensary header Logo"),
 *     @OA\Property(property="app_icon", type="file", description="Dispensary app icon"),
 *     @OA\Property(property="name", type="string", description="Dispensary name", example="Test Dispensary"),
 *     @OA\Property(property="app_name", type="string", description="Dispensary app name", example="Test App"),
 *     @OA\Property(property="email", type="string", description="Dispensary email", example="dispensary@yourmail.com"),
 *     @OA\Property(property="address", type="string", description="Dispensary Address", example="Los Angeles"),
 *     @OA\Property(property="website", type="string", description="Dispensary website", example="testwebsite.com"),
 *     @OA\Property(property="state_licence", type="string", description="State Licence", example="TEST001000"),
 *     @OA\Property(property="timezone", type="string", description="State Licence", example="America/New_York"),
 *     @OA\Property(property="services", type="string", description="Services Offers", example="1,2,3"),
 *     @OA\Property(property="app_color", type="string", description="App Color", example="#000000"),
 *     @OA\Property(property="instagram_url", type="string", description="Instagram Url", example="https://www.instagram.com/"),
 *     @OA\Property(property="facebook_url", type="string", description="Facebook Url", example="https://www.facebook.com/"),
 *     @OA\Property(property="twitter_url", type="string", description="Twitter Url", example="https://www.twitter.com/"),
 *     @OA\Property(property="description", type="string", description="Shop Bio", example="This is shop bio information"),
 *     @OA\Property(property="label_tags", type="string", description="Labels", example="REC 21+ Age Minimum,MED 18+ Age Minimum"),
 * )
 *
 * @OA\RequestBody(
 *     request="ChangePassword",
 *     description="ChangePassword object",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/ChangePasswordSchema")
 * )
 * @OA\Schema(
 *     schema="ChangePasswordSchema",
 *     required={"email","old_password","new_password","password_confirmation"},
 *     @OA\Property(property="email",type="string",format="string",description="Email", example="test@gmail.com"),
 *     @OA\Property(property="old_password",type="string",format="string",description="Old Password", example="password"),
 *     @OA\Property(property="new_password",type="string",format="string",description="New Password", example="password"),
 *     @OA\Property(property="password_confirmation",type="string",format="string",description="Confirm Password", example="password"),
 * )
 */

class Dispensary extends BaseTenant implements HasMedia, Wallet, Transformable
{
    use SoftDeletes, InteractsWithMedia, LogsActivity, Billable, HasWallet, TransformableTrait;

    /*
     * DEFAULT_STATUSES array is used for declaring and validating default allowed statuses for dispensary
     *
     * */
    const MANUALLY_BILLED = 'MANUALLY BILLED';
    const CARD = 'CARD';
    const ENABLED = 'ENABLED';
    const DISABLED = 'DISABLED';
    const US = 'US';
    const CA = 'CA';
    const PENDING = 'PENDING';

    const DEFAULT_STATUSES = ['PENDING', 'LIVE', 'INACTIVE'];

    /*
     * LOG_ACTIONS array will be used for activity logs
     * Keys = statuses which are valid for adding entry in logs
     * Values = Value of 'Description' column in activity log, from which created/active/deactive will be differentiated
     * */
    const LOG_ACTIONS = ['PENDING' => 'active', 'INACTIVE' => 'deactive'];

    /*
     * CUSTOM_NOTE_SLUG is value of 'Description' column in activity log, for custom notes type
     * from which created/active/deactive/custom will be differentiated
     *
     * */
    const CUSTOM_NOTE_SLUG = 'custom';

    /*
     * $logAttributes
     * array containing columns value should be added as an attribute in activity log
     * */
    protected static $logAttributes = ['name'];

    /*
     * $recordEvents
     * array containing actions on which log the activity
     * */
    protected static $recordEvents = ['created'];

    /*
     * SEARCH_FIELDS
     * is used for columns on which search can be applicable
     * */
    const SEARCH_FIELDS = ['id', 'name'];

    /*
     * DEFAULT_LIST_STATUS
     * Dispensaries having this status will be displayed by default in listing
     * */
    const DEFAULT_LIST_STATUS = 'live';

    /*
     * DEFAULT_LIST_ORDER
     * Default listing order of dispensaries
     * */
    const DEFAULT_LIST_ORDER = 'desc';


    protected $table = 'dispensaries';
    //if this array contains column that does not exist in table then it will enter it into data json column
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'own_domain',
        'admin_user_id',
        'bitly_link',
        'setup_fee',
        'services',
        'billing_prompt',
        'service_fee_enabled',
        'service_fee_amount',
        'subscription_type',
        'stripe_id',
        'trial_ends_at',
        'status',
        'alpha_id',
        'app_name',
        'website',
        'state_licence',
        'timezone',
        'product_notes',
        'dispatch_minimum',
        'app_color',
        'instagram_url',
        'facebook_url',
        'twitter_url',
        'description',
        'label_tags',
        ];


    public function shouldGenerateId(): bool
    {
        return false;
    }

    public function getIncrementing()
    {
        return true;
    }
    // these are other columns than data, created_at and Updated_at
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'phone',
            'address',
            'own_domain',
            'admin_user_id',
            'bitly_link',
            'setup_fee',
            'services',
            'billing_prompt',
            'service_fee_enabled',
            'service_fee_amount',
            'subscription_type',
            'stripe_id',
            'trial_ends_at',
            'status',
            'app_name',
            'website',
            'state_licence',
            'timezone',
            'product_notes',
            'dispatch_minimum',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logos')
            ->useDisk('DO')
            ->singleFile();

        $this->addMediaCollection('header_logos')
            ->useDisk('DO')
            ->singleFile();

        $this->addMediaCollection('app_icons')
            ->useDisk('DO')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(50)
            ->height(50);
    }

    public function domains()
    {
        return $this->hasOne(Domain::class);
    }

    public function dispensaryUser()
    {
        return $this->hasOne(DispensaryUser::class);
    }

    public function dispensaryPaymentMethod()
    {
        return $this->hasMany(DispensaryPaymentMethod::class);
    }

    public function dispensaryHourSet()
    {
        return $this->hasMany(DispensaryHourSet::class);
    }

    public function loyaltyProgram()
    {
        return $this->hasMany(LoyaltyProgram::class);
    }

    public function drivers()
    {
        return $this->hasMany(DriverUser::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
