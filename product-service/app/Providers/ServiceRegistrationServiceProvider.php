<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Service\ServiceRegistrationService;
use Illuminate\Support\Facades\Log;


class ServiceRegistrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ServiceRegistrationService::class, function () {
            Log::info("Product Service ServiceRegistrationServiceProvider booting...");
            return new ServiceRegistrationService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Log::info("Product Service ServiceRegistrationServiceProvider booting...");
        $serviceRegistrationService = $this->app->make(ServiceRegistrationService::class);
        $serviceRegistrationService->registerService();

    }
}
