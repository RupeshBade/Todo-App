<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CurlApiService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Links the updated custom facade accessor string directly to our cURL Engine service
        $this->app->singleton('mockapi-curl', function ($app) {
            return new CurlApiService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
