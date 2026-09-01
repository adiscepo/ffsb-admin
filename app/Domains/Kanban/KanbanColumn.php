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
}
