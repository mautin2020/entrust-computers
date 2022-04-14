<?php

namespace App\Providers;

use App\Models\LaptopProduct;
use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\{
    IUser,
    IProduct,
    IProductLedger,
    ILaptopProductLedger,
    ILaptopProduct,
    ILaptopSupply,
    ISupply,
};

use App\Repositories\Eloquent\{
    LaptopProductLedgerRepository,
    LaptopProductRepository,
    LaptopSupplyRepository,
    UserRepository,
    ProductRepository,
    ProductLedgerRepository,
    SupplyRepository,
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IUser::class, UserRepository::class);
        $this->app->bind(IProduct::class, ProductRepository::class);
        $this->app->bind(IProductLedger::class, ProductLedgerRepository::class);
        $this->app->bind(ILaptopProduct::class, LaptopProductRepository::class);
        $this->app->bind(ILaptopProductLedger::class, LaptopProductLedgerRepository::class);
        $this->app->bind(ILaptopSupply::class, LaptopSupplyRepository::class);
        $this->app->bind(ISupply::class, SupplyRepository::class);
    }
}
