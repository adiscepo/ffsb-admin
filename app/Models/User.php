<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Domains\Evaluations\Evaluation;
use App\Domains\Events\Traits\Eventable;
use App\Domains\ProductionHouses\ProductionHouse;
use App\Domains\Roles\Role;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'profile_picture', 'validated'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, Eventable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function getProfilePicture(): ?string
    {
        if ($this->profile_picture) {
            return Storage::url($this->profile_picture);
        }
        return null;
    }

    public function docus(): HasMany
    {
        return $this->hasMany(Docu::class, "found_by");
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function production_houses(): BelongsToMany
    {
        return $this->belongsToMany(ProductionHouse::class, "user_id");
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, "user_id");
    }

    public function assigned_production_houses(): BelongsToMany
    {
        return $this->belongsToMany(ProductionHouse::class);
    }

    public function isValidated(): bool
    {
        return $this->validated;
    }
}
