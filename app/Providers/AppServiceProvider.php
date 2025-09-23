<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SchoolAccount;
use App\Observers\SchoolAccountObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        SchoolAccount::observe(SchoolAccountObserver::class);
    }
}
