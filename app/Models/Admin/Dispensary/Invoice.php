<?php

namespace App\Models\Admin\Dispensary;

use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   schema="InvoiceList",
 *   required={"data"},
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/InvoiceData"))
 * )
 *
 * @OA\Schema(
 *   schema="InvoiceDetails",
 *   required={"data"},
 *   @OA\Property(property="data", type="object", ref="#/components/schemas/InvoiceData")
 * )
 *
 * @OA\Schema(
 *   schema="InvoiceData",
 *   @OA\Property(property="id", type="integer", format="int32", description="Invoice Id", example="1"),
 *   @OA\Property(property="description", type="string", description="Invoice Description", example="1 × Local Per Day 10 (at $10.00 / day)"),
 *   @OA\Property(property="invoice_date", type="string", description="Stripe Invoice Date", example="2022-02-10"),
 *   @OA\Property(property="amount", type="integer", format="int32", description="Stripe Invoice amount", example="1000"),
 *   @OA\Property(property="status",type="string", description="Stripe Invoice status", example="paid"),
 *   @OA\Property(property="invoice_pdf", type="string", description="Stripe Invoice Pdf Url")
 * )
 */

class Invoice extends Model
{
    use DispensaryTrait;

    protected $fillable = [
        'dispensary_id',
        'stripe_invoice_id',
        'stripe_price_id',
        'stripe_subscription_id',
        'invoice_pdf',
        'status',
        'amount',
        'description',
        'invoice_date'
    ];
}
