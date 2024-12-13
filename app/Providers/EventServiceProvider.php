<?php

namespace App\Providers;

use App\Domains\Inventory\Listeners\CreateInventoryOnProductCreated;
use App\Domains\Products\Events\ProductCreated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ProductCreated::class => [
            CreateInventoryOnProductCreated::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
