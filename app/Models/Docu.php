<?php

namespace App\Models;

use App\Models\Pivot\ProductionHouseDocu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Docu extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'summary', 'duration', 'lang', 'subtitles'];

    public function found_by(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function from(): BelongsToMany {
        return $this->belongsToMany(
            ProductionHouse::class, # The pivot table of the relation
            // 'production_house_docu',    # The name of the pivot table in db
            // 'docu_id',                  # The name of the foreign pivot key
            // 'production_house_id',
        );
    }
}
