<?php

namespace App\Domains\Docus;

use App\Domains\Docus\Factory\FieldFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Field extends Model
{
    use HasFactory;

    protected $fillable = ['field', 'color'];

    public function docus(): BelongsToMany
    {
        return $this->belongsToMany(Docu::class);
    }

    protected static function newFactory(): FieldFactory
    {
        return FieldFactory::new();
    }
}
