<?php

namespace App\Domains\Tags;

use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;
    public $timestamps = false;

    public function docus(): MorphToMany
    {
        return $this->morphedByMany(Docu::class, 'taggable');
    }

    public function bugs(): MorphToMany
    {
        return $this->morphedByMany(Tag::class, 'taggable');
    }

    /**
     * Return all the tags for a specific model
     *
     * @param string $model (Model::class)
     * @return Collection
     */
    public static function for($model): Collection
    {
        return Tag::where('model', $model)->orWhere('model', null)?->groupBy('name')->select(['id', 'name'])->get();
    }

    protected static function newFactory(): TagFactory
    {
        return TagFactory::new();
    }
}
