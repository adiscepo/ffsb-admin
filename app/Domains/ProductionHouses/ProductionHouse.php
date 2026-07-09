<?php

namespace App\Domains\ProductionHouses;

use App\Domains\Events\Traits\Eventable;
use App\Domains\ProductionHouses\Factory\ProductionHouseFactory;
use App\Domains\Statuses\Traits\Statusable;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductionHouse extends Model
{
    use HasFactory, Eventable, Statusable;

    protected $fillable = [
        'name',
        'lang',
        'website',
        'contact_email',
        'contact_phone',
        'remark',
    ];

    public function docus(): BelongsToMany
    {
        return $this->belongsToMany(ProductionHouse::class);
    }

    public function assignee(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    protected static function newFactory(): ProductionHouseFactory
    {
        return ProductionHouseFactory::new();
    }
}
