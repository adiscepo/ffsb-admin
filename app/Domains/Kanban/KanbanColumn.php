<?php

declare(strict_types=1);

namespace App\Domains\Kanban;

use App\Domains\Kanban\Kanban;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KanbanColumn extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'kanban_id',
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    public function kanban(): BelongsTo
    {
        return $this->belongsTo(Kanban::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(KanbanCard::class, 'kanban_column_id')->orderBy('position');
    }

    // Each column have a different meaning, the two main ones are
    // "finished" and "dropped", if the task is not in those two column
    // then it's an active task
    public function isFulfilled(): bool
    {
        return $this->type === 'fulfilled';
    }

    public function isDropped(): bool
    {
        return $this->type === 'dropped';
    }

    public function isActive(): bool
    {
        return !($this->isFulfilled() or $this->isDropped());
    }

    public function scopeActive($query)
    {
        return $query->where('type', '=', null);
    }
}
