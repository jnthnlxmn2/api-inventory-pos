<?php

namespace App\Providers;

use App\Repositories\Category\CategoryEloquent;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Customer\CustomerEloquent;
use App\Repositories\Customer\CustomerRepository;
use App\Repositories\Item\ItemEloquent;
use App\Repositories\Item\ItemRepository;
use App\Repositories\ItemQuantity\ItemQuantityEloquent;
use App\Repositories\ItemQuantity\ItemQuantityRepository;
use App\Repositories\Manufacturer\ManufacturerEloquent;
use App\Repositories\Manufacturer\ManufacturerRepository;
use App\Repositories\PurchaseDetail\PurchaseDetailEloquent;
use App\Repositories\PurchaseDetail\PurchaseDetailRepository;
use App\Repositories\Log\LogEloquent;
use App\Repositories\Log\LogRepository;
use App\Repositories\Purchase\PurchaseEloquent;
use App\Repositories\Purchase\PurchaseRepository;
use App\Repositories\SubCategory\SubCategoryEloquent;
use App\Repositories\SubCategory\SubCategoryRepository;
use App\Repositories\UnitType\UnitTypeEloquent;
use App\Repositories\UnitType\UnitTypeRepository;
use App\Repositories\Tax\TaxEloquent;
use App\Repositories\Tax\TaxRepository;
use App\Repositories\Discount\DiscountEloquent;
use App\Repositories\Discount\DiscountRepository;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];
/**
 * Register any authentication / authorization services.
 *
 * @return void
 */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $this->registerPolicies();
        Passport::routes();
    }

    public function register()
    {
        $this->app->singleton(CategoryRepository::class, CategoryEloquent::class);
        $this->app->singleton(SubCategoryRepository::class, SubCategoryEloquent::class);
        $this->app->singleton(CustomerRepository::class, CustomerEloquent::class);
        $this->app->singleton(ManufacturerRepository::class, ManufacturerEloquent::class);
        $this->app->singleton(UnitTypeRepository::class, UnitTypeEloquent::class);
        $this->app->singleton(ItemRepository::class, ItemEloquent::class);
        $this->app->singleton(ItemQuantityRepository::class, ItemQuantityEloquent::class);
        $this->app->singleton(PurchaseRepository::class, PurchaseEloquent::class);
        $this->app->singleton(LogRepository::class, LogEloquent::class);
        $this->app->singleton(PurchaseDetailRepository::class, PurchaseDetailEloquent::class);
        $this->app->singleton(TaxRepository::class, TaxEloquent::class);
        $this->app->singleton(DiscountRepository::class, DiscountEloquent::class);
        //
    }

}
