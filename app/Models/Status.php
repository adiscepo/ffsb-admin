<?php

namespace App\Models;

use App\Domains\Bugs\Bug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

class Status extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;
    public $timestamps = false;

    public function bugs(): MorphToMany
    {
        return $this->morphedByMany(Bug::class, 'statusable');
    }

    /**
     * Return all the statuses for a specific model
     *
     * @param string $model (Model::class)
     * @return Collection
     */
    public static function for($model): Collection
    {
        return Status::where('model', $model)->orWhere('model', null)->groupBy('name')->select(['id', 'name'])->get();
    }
}
