<?php

namespace App\Domains\ProductionHouses;

use App\Domains\Docus\Docu;
use App\Domains\Docus\Enum\DocuLang;
use App\Domains\Events\Traits\Eventable;
use App\Domains\ProductionHouses\Factory\ProductionHouseFactory;
use App\Domains\Statuses\Traits\Statusable;
use App\Domains\Contacts\Traits\Contactable;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductionHouse extends Model
{
    use HasFactory, Eventable, Statusable, Contactable;

    protected $fillable = [
        'name',
        'lang',
        'website',
        'contact_email',
        'contact_phone',
        'remark',
        'user_id',
    ];

    protected $casts = ['lang' => DocuLang::class];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function docus(): BelongsToMany
    {
        return $this->belongsToMany(Docu::class);
    }

    public function assignee(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function hasAssigned(User $user): bool
    {
        return $this->assignee->contains($user);
    }

    protected static function newFactory(): ProductionHouseFactory
    {
        return ProductionHouseFactory::new();
    }
}
