<?php

namespace App\Models\Admin\Customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 *  @OA\Schema(
 *   schema="CustomerList",
 *   required={"data"},
 *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
 *  )
 *  @OA\Schema(
 *   schema="Customer",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp")
 *   }
 *  )
 *
 *  @OA\Schema(schema="CustomerSortsOn", type="array",
 *     @OA\Items(type="string", enum={"id", "name", "email", "phone"})
 * )
 *
 * @OA\Schema(schema="CustomerStatus", type="array",
 *     @OA\Items(type="string", enum={"ACTIVE", "BLOCKED"})
 * )
 *
 *
 *
 * @OA\Schema(schema="CustomerInputData",
 *      @OA\Property(property="logo", type="file", description="Customer "),
 *      @OA\Property(property="first_name", type="string", description="First Name", example="John"),
 *      @OA\Property(property="last_name", type="string", description="Last Name", example="Doe"),
 *      @OA\Property(property="email", type="string", description="Email", example="johndoe@yopmail.com"),
 *      @OA\Property(property="phone", type="string", description="Phone Number", example="9876543210"),
 *      @OA\Property(property="address", type="string", description="Address", example="Los Angeles, CA 90095, USA"),
 *      @OA\Property(property="birth_date", type="string", description="Birth date", example="1990-12-11"),
 *      @OA\Property(property="patient_type", type="string", description="Patient Type (MEDICAL/RECREATIONAL)", example="MEDICAL"),
 *      @OA\Property(property="patient_number", type="string", description="Patient Number", example="PT101"),
 *      @OA\Property(property="patient_doctor", type="string", description="Patient Doctor", example="DOC101"),
 *      @OA\Property(property="medical_expire_date", type="string", description="Med Expiration Date", example="2021-12-31"),
 *      @OA\Property(property="territory_id", type="string", description="Selected Territory Id", example="10"),
 *      @OA\Property(property="verify_status", type="string", description="Status", example="UNVERIFIED"),
 *  )
 *  @OA\Schema(schema="CustomerUpdateInputData",
 *      @OA\Property(property="first_name", type="string", description="First Name", example="John"),
 *      @OA\Property(property="last_name", type="string", description="Last Name", example="Doe"),
 *      @OA\Property(property="email", type="string", description="Email", example="johndoe@yopmail.com"),
 *      @OA\Property(property="phone", type="string", description="Phone Number", example="9876543210"),
 *      @OA\Property(property="address", type="string", description="Address", example="Los Angeles, CA 90095, USA"),
 *      @OA\Property(property="birth_date", type="string", description="Birth date", example="1990-12-11"),
 *      @OA\Property(property="patient_type", type="string", description="Patient Type (MEDICAL/RECREATIONAL)", example="MEDICAL"),
 *      @OA\Property(property="patient_number", type="string", description="Patient Number", example="PT101"),
 *      @OA\Property(property="patient_doctor", type="string", description="Patient Doctor", example="DOC101"),
 *      @OA\Property(property="medical_expire_date", type="string", description="Med Expiration Date", example="2021-12-31"),
 *      @OA\Property(property="territory_id", type="string", description="Selected Territory Id", example="10"),
 *      @OA\Property(property="verify_status", type="string", description="Status", example="UNVERIFIED"),
 *  )
 *
 * @OA\Schema(schema="CustomerStatusUpdateInputData",
 *      @OA\Property(property="status", type="string", description="Status", example="ACTIVE"),
 *  )

 *
 *     @OA\Schema(schema="CustomerDocumentsData",
 *      @OA\Property(property="document_type", type="string", enum={"card", "medical", "other"}, description="Document Type"),
 *      @OA\Property(property="document_file", type="file", description="Please select document"),
 *      @OA\Property(property="customer_id", type="string", description="Customer Id", example="1")
 *
 *  )
 *
 * @OA\Schema(schema="CustomerImportData",
 *      @OA\Property(property="customer_data", type="file", description="Please select csv")
 *  )
 *
 * * @OA\RequestBody(
 *     request="CustomerRequest",
 *     description="Customer Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/CustomerInputData")
 *  )
 *
 */

class Customer extends Model implements Transformable
{
    use SoftDeletes, TransformableTrait;
    /*
     * SEARCH_FIELDS
     * is used for columns on which search can be applicable
     * */
    const SEARCH_FIELDS = ['id', 'email', 'phone'];

    /*
     * DEFAULT_STATUSES array is used for declaring and validating default allowed statuses for customers
     *
     * */
    const DEFAULT_STATUSES = ['VERIFIED', 'UNVERIFIED', 'DECLINED'];


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

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'birth_date',
        'status',
        ];

    public function dispensaryCustomer()
    {
        return $this->hasMany(DispensaryCustomer::class);
    }
}
