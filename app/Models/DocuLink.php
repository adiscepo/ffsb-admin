<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocuLink extends Model
{
    /** @use HasFactory<\Database\Factories\DocuLinkFactory> */
    use HasFactory;

    protected $fillable = ['url', 'password', 'deadline'];

    public function password() : ?string {
        return $this->password ? $this->password : null; 
    }

    public function for(): BelongsTo {
        return $this->belongsTo(Docu::class);
    }
}
