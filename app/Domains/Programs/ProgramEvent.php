<?php

namespace App\Domains\Programs;

use App\Domains\Events\Traits\Eventable;
use App\Models\EditionYear;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ProgramEvent extends Model
{
    use HasFactory, Eventable;

    protected $fillable = ['program_id', 'start_event', 'duration', 'kind', 'payload'];

    protected $casts = [
        'payload' => 'array',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    // protected static function newFactory(): ProgramFactory
    // {
    //     return ProgramFactory::new();
    // }
}
