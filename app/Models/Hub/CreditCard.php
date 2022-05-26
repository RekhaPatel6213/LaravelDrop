<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(schema="CreditCardSortsOn", type="array",
 *     @OA\Items(type="string", enum={"brand", "card_number", "exp_month", "exp_year", "is_default"})
 * )
 *
 * @OA\Schema(
 *  schema="CreditCardList",
 *  @OA\Property(property="data", type="array", @OA\Items(
 *      @OA\Property(property="id", type="string", description="Credit Card Id", example="1"),
 *      @OA\Property(property="brand", type="string", description="Credit Card Brand Name", example="MasterCard"),
 *      @OA\Property(property="card_number", type="string", description="Credit Card last 4 digit", example="5100"),
 *      @OA\Property(property="exp_month", type="string", description="Credit Card expiry month", example="12"),
 *      @OA\Property(property="exp_year", type="string", description="Credit Card expiry year", example="26"),
 *      @OA\Property(property="is_default", type="string", description="Credit Card Default Status", example="YES", enum={"YES", "NO"})
 *  ))
 * )
 *
 * @OA\Schema(
 *  schema="CreditCardData",
 *  @OA\Property(property="stripe_token", type="string", description="Stripe Token", example="tok_1KS3lJSJCzzs25Bn8ApMgu7O"),
 *  @OA\Property(property="email", type="string", description="Email Address", example="jhondeo@yopmil.com"),
 *  @OA\Property(property="name",type="string", description="Name", example="jhon Deo")
 * )
 */

class CreditCard extends Model
{
    use HasFactory;
    use DispensaryTrait;
    use SoftDeletes;

    public const YES = 'YES';
    public const NO = 'NO';

    //protected $table = 'credit_cards';

    protected $fillable = [
        'strip_card',
        'name',
        'email',
        'card_number',
        'brand',
        'exp_month',
        'exp_year',
        'funding',
        'country',
        'is_default',
    ];
}
