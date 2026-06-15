<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CurlApiService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('mockapi-curl', function ($app) {
            return new CurlApiService();
        });
    }

    public function boot(): void {}
}
