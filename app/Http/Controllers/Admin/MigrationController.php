<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\AdminUser;
use App\Models\Admin\Dispensary\Dispensary;
use App\Models\Admin\Dispensary\Subscription;
use App\Models\Admin\Dispensary\Invoice;
use App\Models\Repositories\Admin\Dispensary\DispensaryRepository;
use App\Services\Admin\MigrationService;
use Carbon\Carbon;
use DB;

class MigrationController extends Controller
{
    protected $databaseNew;
    protected $databaseOld;
    protected $adminUserService, $smsService, $migrationService;
    protected $dispensaryRepository;

    public function __construct(DispensaryRepository $dispensaryRepository, MigrationService $migrationService)
    {
        $this->databaseNew = DB::connection('mysql');
        $this->databaseOld = DB::connection('mysql2');
        $this->adminUserService = app('adminService');
        $this->smsService = app('SMS.Service');
        $this->dispensaryRepository = $dispensaryRepository;
        $this->migrationService = $migrationService;
    }

    public function migrateAdminUser()
    {
        $adminUsers = $this->databaseOld->table('admins')->get();
        if($adminUsers)
        {
            foreach($adminUsers as $user)
            {
                if(!AdminUser::whereEmail($user->email)->first())
                {
                    $user->last_login = $user->last_login !== 0 ? date('Y-m-d H:i:s', $user->last_login) : NULL;
                    $user->status = $user->status === 1 ? AdminUser::ACTIVE : AdminUser::INACTIVE;
                    
                    $adminUser = $this->adminUserService->storeAdmin((array)$user);
                    if($user->is_deleted === 1){
                        $adminUser->deleted_at = Carbon::now()->toDateTimeString();
                        $adminUser->save();
                    }
                }
            }
        }
        return response()->json(['success' => true, 'message' => 'Admin User Data Migrate Successfully.']);
    }

    public function migrateDispensary()
    {
        $dispensaries = $this->databaseOld->table('dispensaries')->get();
        if($dispensaries)
        {
            foreach($dispensaries as $dispensary)
            {
                if(!Dispensary::whereEmail($dispensary->disp_email)->first()){
                    $dispensaryOldId = $dispensary->disp_id;

                    $dispensaryData = [
                        'name' => $dispensary->disp_name, 
                        'email' => $dispensary->disp_email, 
                        'phone' => $dispensary->disp_phone, 
                        'address' => $dispensary->address_label,
                        'own_domain' => $dispensary->own_domain,
                        'admin_user_id' => $dispensary->account_id,
                        'bitly_link' => $dispensary->branch,
                        'setup_fee' => $dispensary->setup_fee,
                        'services' => $dispensary->service_offer,
                        'billing_prompt' => $dispensary->billing_prompt,
                        'service_fee_enabled' => $dispensary->billing_required,
                        'service_fee_amount' => $dispensary->service_fee_amount,
                        'subscription_type' => $dispensary->subscription_type,
                        'status' => $dispensary->is_live === 1 ? 'LIVE' : ($dispensary->is_hidden === 1 ? 'INACTIVE' : 'PENDING'),
                        'created_at' => date('Y-m-d H:i:s', $dispensary->created_at),
                        'updated_at' => date('Y-m-d H:i:s', $dispensary->created_at),
                        'stripe_id' => $dispensary->stripe_customer
                    ];

                    $dispensaryObj = $this->dispensaryRepository->store($dispensaryData);
                    $dispensaryNewId = $dispensaryObj->id;

                    $this->migrateDispensarySMS($dispensaryOldId, $dispensaryNewId);
                    $this->migrateDispensarySubscription($dispensaryOldId, $dispensaryNewId);
                    $this->migrateDispensaryInvoice($dispensaryOldId, $dispensaryNewId);

                    if($dispensary->is_deleted === 1){
                        $this->dispensaryRepository->delete($dispensaryObj->id);
                    }

                    dd($dispensaryObj);
                }
            }
        }
        return response()->json(['success' => true, 'message' => 'Dispensary Data Migrate Successfully.']);
    }

    public function migrateDispensarySMS($dispensaryOldId, $dispensaryNewId)
    {
        $credits = $this->databaseOld->table('dispensary_credits')->where('disp_id', $dispensaryOldId)->get();

        if($credits)
        {
            foreach($credits as $credit)
            {
                $endDate = date('Y-m-d H:i:s', $credit->expired_at);
                $confirmed = $credit->expired_at >= strtotime("now") ? true : false;

                $dispensary = Dispensary::find($dispensaryNewId);
                $dispensary->deposit($credit->amount,['expiry_date' => $endDate], $confirmed);
            }
        }
    }

    public function migrateDispensarySubscription($dispensaryOldId, $dispensaryNewId)
    {
        $subscriptions = $this->databaseOld->table('client_subscriptions')->where('disp_id', $dispensaryOldId)->get();
        if($subscriptions)
        {
            foreach($subscriptions as $subscription)
            {
                $subscriptionObj = new Subscription();
                $subscriptionObj->dispensary_id = $dispensaryNewId;
                $subscriptionObj->name = 'sms';
                $subscriptionObj->stripe_id = $subscription->subscription_id;
                $subscriptionObj->stripe_status = $subscription->status;
                $subscriptionObj->stripe_price = $subscription->plan_id;
                $subscriptionObj->quantity = 1;
                $subscriptionObj->ends_at = date('Y-m-d H:i:s', $subscription->canceled_at);
                $subscriptionObj->save();

                $itemData = [
                    "subscription_id" => $subscriptionObj->id,
                    "stripe_id" => NULL,
                    "stripe_product" => NULL,
                    "stripe_price" => $subscription->plan_id,
                    "quantity" => 1
                ];
                DB::table('subscription_items')->insert($itemData);
            }
        }
    }

    public function migrateDispensaryInvoice($dispensaryOldId, $dispensaryNewId)
    {
        $invoices = $this->databaseOld->table('client_invoices')->where('disp_id', $dispensaryOldId)->get();
        if($invoices)
        {
            foreach($invoices as $invoice)
            {
                $invoiceObj = new Invoice();
                $invoiceObj->dispensary_id = $dispensaryNewId;
                $invoiceObj->stripe_invoice_id = $invoice->stripe_invoice_id;
                $invoiceObj->invoice_pdf = $invoice->stripe_invoice_link !== null ? $invoice->stripe_invoice_link : $invoice->invoice_link;
                $invoiceObj->status = $invoice->status;
                //$invoiceObj->stripe_price_id = $subscription->subscription_id;
                //$invoiceObj->stripe_subscription_id = $subscription->status;
                //$invoiceObj->amount = 0;
                $invoiceObj->save();
            }
        }
    }

    public function migrateCategory()
    {
        try {
            $this->migrationService->migrateCategories();
            return response()->json(['success' => true, 'message' => 'Category Data Migrate Successfully.']);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
