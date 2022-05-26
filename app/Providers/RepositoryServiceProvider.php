<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Repositories\Brand\BrandRepository;
use App\Models\Repositories\Contracts\Brand\BrandInterface;
use App\Models\Repositories\Admin as AdminRepository;
use App\Models\Repositories\Contracts\Admin as AdminContracts;
use App\Models\Repositories\Contracts\Driver\DriverUserInterface;
use App\Models\Repositories\Contracts\Hub\DealInterface;
use App\Models\Repositories\Contracts\Hub\MessageBoxInterface;
use App\Models\Repositories\Contracts\Location\LocationInterface;
use App\Models\Repositories\Contracts\Territory as TerritoryContracts;
use App\Models\Repositories\Contracts\Hub\InventoryInterface;
use App\Models\Repositories\Contracts\Admin\Customer\CustomerRepository as CustomerInterface;
use App\Models\Repositories\Contracts\Admin\Customer\DispensaryCustomerRepository as DispensaryCustomerInterface;
use App\Models\Repositories\Contracts\Admin\Dispensary\DispensaryHourSetRepository as DispensaryHourSetInterface;
use App\Models\Repositories\Contracts\Admin\Dispensary\DispensaryPaymentMethodRepository as DispensaryPaymentMethodInterface;
use App\Models\Repositories\Contracts\Admin\Dispensary\DispensaryRepository as DispensaryInterface;
use App\Models\Repositories\Contracts\Admin\Dispensary\DispensaryTimingRepository as DispensaryTimingInterface;
use App\Models\Repositories\Contracts\Admin\Dispensary\DomainRepository as DomainInterface;
use App\Models\Repositories\Contracts\Admin\Dispensary\DropOffOptionRepository as DropOffOptionInterface;
use App\Models\Repositories\Contracts\Admin\Dispensary\LoyaltyProgramRepository as LoyaltyProgramInterface;
use App\Models\Repositories\Contracts\Admin\Dispensary\PurchaseLimitInterface;
use App\Models\Repositories\Contracts\Territory\TaxesCostsSettingInterface;
use App\Models\Repositories\Territory\TaxesCostsSettingRepository;
use App\Models\Repositories\Contracts\Hub\FaqInterface;
use App\Models\Repositories\Contracts\Hub\PageInterface;
use App\Models\Repositories\Contracts\Hub\RewardInterface;
use App\Models\Repositories\Contracts\Hub\NotificationInterface;
use App\Models\Repositories\Contracts\Admin\Dispensary\DispensaryUserRepository as DispensaryUserInterface;
use App\Models\Repositories\Driver\DriverUserRepository;
use App\Models\Repositories\Hub\DealRepository;
use App\Models\Repositories\Hub\MessageBoxRepository;
use App\Models\Repositories\Location\LocationRepository;
use App\Models\Repositories\Territory\TerritoryModuleRepository;
use App\Models\Repositories\Territory\TerritoryRepository;
use App\Models\Repositories\Hub\InventoryRepository;
use App\Models\Repositories\Admin\Customer\CustomerRepository;
use App\Models\Repositories\Admin\Customer\DispensaryCustomerRepository;
use App\Models\Repositories\Admin\Dispensary\DispensaryHourSetRepository;
use App\Models\Repositories\Admin\Dispensary\DispensaryPaymentMethodRepository;
use App\Models\Repositories\Admin\Dispensary\DispensaryRepository;
use App\Models\Repositories\Admin\Dispensary\DispensaryTimingRepository;
use App\Models\Repositories\Admin\Dispensary\DomainRepository;
use App\Models\Repositories\Admin\Dispensary\DropOffOptionRepository;
use App\Models\Repositories\Admin\Dispensary\LoyaltyProgramRepository;
use App\Models\Repositories\Hub\FaqRepository;
use App\Models\Repositories\Hub\PageRepository;
use App\Models\Repositories\Hub\RewardRepository;
use App\Models\Repositories\Hub\NotificationRepository;
use App\Models\Repositories\Admin\Dispensary\DispensaryUserRepository;
use App\Models\Repositories\Contracts\Hub\AuditInterface;
use App\Models\Repositories\Hub\AuditRepository;
use App\Models\Repositories\Contracts\Hub\BannerInterface;
use App\Models\Repositories\Hub\BannerRepository;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $contractRepositories = [
            [ InventoryInterface::class => InventoryRepository::class],
            [ CustomerInterface::class => CustomerRepository::class],
            [ DispensaryCustomerInterface::class => DispensaryCustomerRepository::class ],
            [ DispensaryHourSetInterface::class => DispensaryHourSetRepository::class ],
            [ DispensaryPaymentMethodInterface::class => DispensaryPaymentMethodRepository::class ],
            [ DispensaryInterface::class => DispensaryRepository::class ],
            [ DispensaryTimingInterface::class => DispensaryTimingRepository::class ],
            [ DomainInterface::class => DomainRepository::class ],
            [ DropOffOptionInterface::class => DropOffOptionRepository::class ],
            [ LoyaltyProgramInterface::class => LoyaltyProgramRepository::class ],
            [ PurchaseLimitInterface::class => AdminRepository\Dispensary\PurchaseLimitRepository::class ],
            [ TaxesCostsSettingInterface::class => TaxesCostsSettingRepository::class ],
            [ DriverUserInterface::class => DriverUserRepository::class ],
            [ LocationInterface::class => LocationRepository::class ],
            [ TerritoryContracts\TerritoryInterface::class => TerritoryRepository::class ],
            [ TerritoryContracts\TerritoryModuleInterface::class => TerritoryModuleRepository::class ],
            [ MessageBoxInterface::class => MessageBoxRepository::class ],
            [ FaqInterface::class => FaqRepository::class ],
            [ PageInterface::class => PageRepository::class ],
            [ RewardInterface::class => RewardRepository::class ],
            [ NotificationInterface::class => NotificationRepository::class ],
            [ AuditInterface::class => AuditRepository::class ],
            [ DealInterface::class => DealRepository::class ],
            [ BrandInterface::class => BrandRepository::class ],
            [ BannerInterface::class => BannerRepository::class ],
            [ DispensaryUserInterface::class => DispensaryUserRepository::class ], 
        ];
        foreach ($contractRepositories as $repository => $contract) {
            $this->app->bind($repository, $contract);
        }
        //:end-bindings:
    }
}