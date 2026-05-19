<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocuLink extends Model
{
    /** @use HasFactory<\Database\Factories\DocuLinkFactory> */
    use HasFactory;

    protected $fillable = ['url', 'password', 'deadline', 'docu_id'];

    protected $casts = ['deadline' => 'immutable_datetime'];
    public function password() : ?string {
        return $this->password ? $this->password : null; 
    }

    public function for(): BelongsTo {
        return $this->belongsTo(Docu::class);
    }

    public function stillAvailable(): bool {
        if ($this->deadline) {
            return $this->deadline > now();
        }
        return true;
    }

    public function remainingDays(): string {
        $remaining = now()->locale('fr')->sub($this->deadline)->longAbsoluteDiffForHumans();
        if ($this->stillAvailable()) {
            return 'Dispo encore ' . $remaining;
        }
        return 'Plus dispo depuis ' . $remaining;
    }
}
