<?php

namespace App\Models;

use App\Models\Enum\DocuTarget;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Docu extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'summary', 'duration', 'lang', 'subtitles'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation to the production houses that own the documentary
     *
     * @return BelongsToMany
     */
    public function from(): BelongsToMany {
        return $this->belongsToMany(
            ProductionHouse::class, # The pivot table of the relation
            // Don't need of the following arguments, they are found
            // automatically by Laravel
            // 'production_house_docu',    # The name of the pivot table in db
            // 'docu_id',                  # The name of the foreign pivot key
            // 'production_house_id',
        );
    }

    /**
     * Relation to the link to see the documentary
     *
     * @return HasMany
     */
    public function see_at(): HasMany {
        return $this->hasMany(DocuLink::class);
    }

    public function fields(): BelongsToMany {
        return $this->belongsToMany(Field::class);
    }

    public function tags(): BelongsToMany {
        return $this->belongsToMany(Tag::class);
    }

    public function evaluations(): HasMany {
        return $this->hasMany(Evaluation::class);
    }

    public function target() {
        if ($this->target) {
            return DocuTarget::from($this->target)->label();
        }
    }
}
