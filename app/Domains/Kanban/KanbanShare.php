<?php

namespace App\Domains\Kanban;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KanbanShare extends Model
{
    protected $fillable = [
        'kanban_id',
        'user_id',
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Kanban::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
