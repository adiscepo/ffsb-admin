<?php

namespace App\Domains\Events\Traits;

use App\Domains\Events\Event;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Eventable
{
    public function events(): MorphToMany
    {
        return $this->morphToMany(Event::class, 'eventable');
    }
}
