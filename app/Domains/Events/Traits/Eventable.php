<?php

namespace App\Domains\Events\Traits;

use App\Domains\Events\Event;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait Eventable
{
    public function events(): MorphToMany
    {
        return $this->morphToMany(Event::class, 'eventable');
    }

    public function comments(): Collection
    {
        return $this->events->filter(function ($event) {
            return $event->type == 'comment';
        });
    }
}
