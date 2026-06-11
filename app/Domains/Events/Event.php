<?php

namespace App\Domains\Events;

use App\Domains\Bugs\Bug;
use App\Domains\Evaluations\Evaluation;
use App\Domains\Events\Factory\EventFactory;
use App\Domains\Docus\Docu;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['author_id', 'type', 'payload'];

    protected $casts = ['payload' => 'array'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function docus(): MorphToMany
    {
        return $this->morphedByMany(Docu::class, 'eventable');
    }

    public function evaluations(): MorphToMany
    {
        return $this->morphedByMany(Evaluation::class, 'eventable');
    }

    public function bugs(): MorphToMany
    {
        return $this->morphedByMany(Bug::class, 'eventable');
    }

    // public static function for($model): Collection
    // {
    //     return Event::where('model', $model)->orWhere('model', null)?->groupBy('name')->select(['id', 'name'])->get();
    // }

    /**
     * Set the factory (because use a non-common path)
     *
     */
    protected static function newFactory(): EventFactory
    {
        return EventFactory::new();
    }
};
