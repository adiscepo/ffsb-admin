<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Docu extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'summary', ''
    ];

    public function author(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    // public function tags(): HasMany {
    //     // return $this->belongsTo(Tag::class);
    // }
}
