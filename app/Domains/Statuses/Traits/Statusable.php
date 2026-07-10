<?php

namespace App\Domains\Statuses\Traits;

use App\Domains\Statuses\Status;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait Statusable
{
    public function statuses(): MorphToMany
    {
        return $this->morphToMany(Status::class, 'statusable');
    }

    public function all_statuses(): Collection
    {
        return Status::where('model', $this::class)->get();
    }
}
