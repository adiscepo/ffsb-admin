<?php

namespace App\Models;

use App\Models\Pivot\ProductionHouseDocu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionHouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'website',
        'contact_email',
        'contact_phone',
        'remark',
    ];

    public function docus(): BelongsToMany {
        return $this->belongsToMany(ProductionHouse::class);
    }
}
