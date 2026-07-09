<?php

namespace App\Domains\Statuses\Traits;

use App\Domains\Statuses\Status;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Statusable
{
    public function statuses(): MorphToMany
    {
        return $this->morphToMany(Status::class, 'statusable');
    }
}
