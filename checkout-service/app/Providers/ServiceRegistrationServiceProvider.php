<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Foundation\Application;
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
            return new ServiceRegistrationService();
        });

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        /* 
            @TODO:
            remember to Move the service registrationlogic out of the service boot process and into a separate command or job
            because no service will start if the gateway service is down. 
        */ 
        Log::info("Checkout Service ServiceRegistrationServiceProvider booting...");

        $serviceRegistrationService = $this->app->make(ServiceRegistrationService::class);
        $serviceRegistrationService->registerService();

        Log::info("Checkout Service ServiceRegistrationServiceProvider booted.");

    }
}
