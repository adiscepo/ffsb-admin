<?php

use App\Notifications\NotificationServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;

return [
    AppServiceProvider::class,
    NotificationServiceProvider::class,
    FortifyServiceProvider::class,
];
