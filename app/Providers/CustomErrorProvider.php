<?php

namespace App\Providers;

use App\Services\CustomErrorsService;
use Illuminate\Support\ServiceProvider;

class CustomErrorProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CustomErrorsService::class, function($app){
            return new CustomErrorsService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
