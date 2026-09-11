<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::subscribe(NotificationEventSubscriber::class);
    }
}
