<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use App\Jobs\InventoryCreated;
use App\Jobs\ProductCreated;
use App\Jobs\ProductDeleted;
use App\Jobs\ProductUpdated;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Log::info("Attempting to receive PRODUCT DATA FOR CREATION");
        $this->app->bindMethod([ProductCreated::class, 'handle'], fn($job) => $job->handle());

        Log::info("Attempting to receive PRODUCT DATA FOR UPDATION");
        $this->app->bindMethod([ProductUpdated::class, 'handle'], fn($job) => $job->handle());

        Log::info("Attempting to receive PRODUCT DATA FOR DELETION");
        $this->app->bindMethod([ProductDeleted::class, 'handle'], fn($job) => $job->handle());

        Log::info("Attempting to receive INVENTORY DATA FOR CREATION");
        $this->app->bindMethod([InventoryCreated::class, 'handle'], fn($job) => $job->handle());


    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
