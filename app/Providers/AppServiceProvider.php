<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\RewardRedemption;
use App\Observers\RewardRedemptionObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register RewardRedemption Observer
        RewardRedemption::observe(RewardRedemptionObserver::class);
    }
}
