<?php

namespace App\Providers;

use App\Repositories\Contracts\PropertyRepositoryContract;
use App\Repositories\Contracts\SearchProfileRepositoryContract;
use App\Repositories\PropertyRepository;
use App\Repositories\SearchProfileRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PropertyRepositoryContract::class, PropertyRepository::class);
        $this->app->singleton(SearchProfileRepositoryContract::class, SearchProfileRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
