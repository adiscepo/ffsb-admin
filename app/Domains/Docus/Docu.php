<?php

namespace App\Domains\Docus;

use App\Domains\Evaluations\Evaluation;
use App\Domains\Evaluations\EvaluationCriterion;

use App\Domains\Docus\DocuLink;
use App\Domains\Docus\Field;
use App\Domains\Docus\Enum\DocuTarget;
use App\Domains\Docus\Factory\DocuFactory;
use App\Domains\Events\Traits\Eventable;
use App\Domains\Tags\Traits\Taggable;
use App\Models\User;
use App\Domains\ProductionHouses\ProductionHouse;
use App\Models\EditionYear;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Docu extends Model
{
    use HasFactory;
    use Eventable, Taggable;

    protected $fillable = ['title', 'summary', 'duration', 'year', 'user_id', 'lang', 'subtitles', 'target', 'comment', 'edition_year_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation to the production houses that own the documentary
     *
     * @return BelongsToMany
     */
    public function from(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductionHouse::class, # The pivot table of the relation
            // Don't need of the following arguments, they are found
            // automatically by Laravel
            // 'production_house_docu',    # The name of the pivot table in db
            // 'docu_id',                  # The name of the foreign pivot key
            // 'production_house_id',
        );
    }

    public function edition_year(): BelongsTo
    {
        return $this->belongsTo(EditionYear::class);
    }

    /**
     * Relation to the link to see the documentary
     *
     * @return HasMany
     */
    public function see_at(): HasMany
    {
        return $this->hasMany(DocuLink::class);
    }

    public function fields(): BelongsToMany
    {
        return $this->belongsToMany(Field::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function published_evaluations()
    {
        return $this->evaluations->reject(function (Evaluation $eval) {
            return $eval->isDraft();
        });
    }

    public function target()
    {
        if ($this->target) {
            return DocuTarget::from($this->target)->label();
        }
    }

    public function seenBy(int $user_id): bool
    {
        return $this->evaluations()->where(['user_id' => $user_id, 'draft' => false])->count() > 0;
    }

    public function hasDraftEvaluationFrom(int $user_id): bool
    {
        $evaluation = $this->evaluations()->where('user_id', $user_id)->first();
        if ($evaluation != null) {
            return $evaluation->isDraft();
        }
        return false;
    }

    public function noteFrom(int $user_id): ?int
    {
        if ($this->seenBy($user_id)) {
            return $this->evaluations()->where(['user_id' => $user_id, 'draft' => false])->first()->note();
        }
        return null;
    }

    public function numberEvaluations(): int
    {
        return $this->evaluations()->where('draft', false)->count();
    }
    /**
     * Return the average note for all the evaluation on the docu
     *
     * @return integer
     */
    public function averageNoteEvaluation(): int
    {
        $note = 0;
        if ($this->evaluations()->count() > 0) {
            foreach ($this->evaluations as $evaluation) {
                $note += $evaluation->note();
            }
            return $note / $this->evaluations->count();
        }
        return $note;
    }

    public function maxNote(): int
    {
        // 5 is the maximum notation for a criterion in a evaluation.
        return EvaluationCriterion::count('id') * 5;
    }

    protected static function newFactory(): DocuFactory
    {
        return DocuFactory::new();
    }
}
