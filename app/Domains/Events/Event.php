<?php

namespace App\Domains\Events;

use App\Domains\Bugs\Bug;
use App\Domains\Evaluations\Evaluation;
use App\Domains\Events\Factory\EventFactory;
use App\Domains\Docus\Docu;
use App\Domains\Kanban\Kanban;
use App\Domains\Kanban\KanbanCard;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

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

    public function kanbanCards(): MorphToMany
    {
        return $this->morphedByMany(KanbanCard::class, 'eventable');
    }

    public function isRelatedTo(): array
    {
        $res = collect();
        $res = $res->merge($this->bugs);
        $res = $res->merge($this->kanbanCards);
        $res = $res->merge($this->evaluations);
        $res = $res->merge($this->docus);
        return $res->all();
    }

    public function isEdited(): bool
    {
        if (isset($this->payload['edited']) && $this->payload['edited']) {
            return true;
        }
        return false;
    }

    public function isComment(): bool
    {
        return $this->type == 'comment';
    }

    public function scopeOld($query)
    {
        return $query->where('updated_at', '<', now()->subWeek(2));
    }

    /**
     * Set the factory (because use a non-common path)
     *
     */
    protected static function newFactory(): EventFactory
    {
        return EventFactory::new();
    }
};
