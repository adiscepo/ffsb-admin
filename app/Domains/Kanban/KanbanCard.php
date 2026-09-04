<?php

declare(strict_types=1);

namespace App\Domains\Kanban;

use App\Domains\Events\Traits\Eventable;
use App\Domains\Tags\Traits\Taggable;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KanbanCard extends Model
{
    use HasFactory, Eventable;

    protected $fillable = [
        'title',
        'description',
        'position',
        'kanban_id',
        'kanban_column_id',
        'deadline',
        'user_id',
    ];

    protected $casts = [
        'position' => 'integer',
        'deadline' => 'datetime:Y-m-d H:00',
    ];

    public function kanban(): BelongsTo
    {
        return $this->belongsTo(Kanban::class);
    }

    public function column(): BelongsTo
    {
        return $this->belongsTo(KanbanColumn::class, 'kanban_column_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignee(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
