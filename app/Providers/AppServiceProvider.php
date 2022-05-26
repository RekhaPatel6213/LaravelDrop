<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;
use App\Services\Admin\AdminService;
use App\Models\Repositories\Admin\AdminUserRepository;
use App\Services\Admin\Dispensary\SMSService;
use App\Services\Admin\SettingsService;
use App\Services\Admin\Dispensary\SubscriptionPriceService;
use App\Services\Admin\FaqService;
use App\Services\Admin\PageService;
use App\Services\Hub\CategoryService;
use App\Models\Repositories\Hub\DispensaryCategoryRepository;
use App\Services\Hub\ProductService;
use App\Services\Hub\BulkTransferService;
use App\Services\Hub\BulkTemplateService;
use App\Models\Repositories\Hub\BulkTemplateRepository;
use App\Models\Repositories\Hub\ProductRepository;
use App\Models\Repositories\GenericImportRepository;
use App\Services\Hub\ProductInventoryService;
use Laravel\Cashier\Cashier;
use App\Models\Admin\Dispensary\Dispensary;
use Vanilo\Category\Contracts\Taxon as TaxonContract;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Services\Hub\CreditCardService;
use App\Models\Repositories\Hub\CreditCardRepository;
use App\Models\Repositories\Admin\Dispensary\InvoiceRepository;
use App\Models\Admin\Dispensary\Subscription;
use App\Models\Admin\Dispensary\SubscriptionItem;
use App\Services\Hub\AuditService;
use App\Models\Repositories\Hub\AuditRepository;
use Stripe\Stripe;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('fractalManager', function () {
            return new Manager();
        });

        $this->app->singleton('adminService', function ($app) {
            return new AdminService($app->make(AdminUserRepository::class));
        });

        $this->app->singleton('settingsService', function () {
            return new SettingsService();
        });

        $this->app->singleton('subscriptionPriceService', function () {
            return new SubscriptionPriceService();
        });

        $this->app->singleton('SMS.Service', function () {
            return new SMSService();
        });

        $this->app->singleton('faqService', function () {
            return new FaqService();
        });

        $this->app->singleton('pageService', function () {
            return new PageService();
        });

        $this->app->singleton('categoryService', function ($app) {
            return new CategoryService($app->make(DispensaryCategoryRepository::class));
        });

        $this->app->singleton('productService', function ($app) {
            return new ProductService($app->make(ProductRepository::class), $app->make(GenericImportRepository::class));
        });

        $this->app->singleton('inventoryService', function ($app) {
            return new ProductInventoryService();
        });

        $this->app->singleton('bulkTransferService', function ($app) {
            return new BulkTransferService();
        });

        $this->app->singleton('bulkTemplateService', function ($app) {
            return new BulkTemplateService($app->make(BulkTemplateRepository::class));
        });

        $this->app->singleton('creditCardService', function ($app) {
            return new CreditCardService($app->make(CreditCardRepository::class), $app->make(InvoiceRepository::class));
        });
        
        $this->app->singleton('auditService', function ($app) {
            return new AuditService($app->make(AuditRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Cashier::useCustomerModel(Dispensary::class);
        Cashier::useSubscriptionModel(Subscription::class);
        Cashier::useSubscriptionItemModel(SubscriptionItem::class);

        Stripe::setApiKey(config('services.stripe.secret'));

        $this->app->concord->registerModel(
            TaxonContract::class, \App\Models\Hub\Category::class
        );

        Relation::morphMap([
            'product' => \App\Models\Hub\Product::class,
            'product_detail' => \App\Models\Hub\ProductDetail::class,
            'Territory' => \App\Models\Territory\Territory::class,
            'Driver' => \App\Models\Driver\DriverUser::class,
            'Inventory' => \App\Models\Hub\Inventory::class,
            'Category' => \App\Models\Hub\Category::class,
            'admin' => \App\Models\Admin\AdminUser::class,
            'dispensary_user' => \App\Models\Admin\Dispensary\DispensaryUser::class,
            'Banner' => \App\Models\Hub\Banner::class,
            'Reward' => \App\Models\Hub\Reward::class,
            'Location' => \App\Models\Location\Location::class,
        ]);
    }
}
