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
        // Links the custom facade accessor string directly to our cURL Engine service
        $this->app->singleton('jsonplaceholder-curl', function ($app) {
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
