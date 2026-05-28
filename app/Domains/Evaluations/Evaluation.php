<?php

namespace App\Domains\Evaluations;

use App\Models\User;
use App\Models\Docu;
use App\Domains\Evaluations\EvaluationCriterion;
use App\Domains\Evaluations\Factory\EvaluationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Evaluation extends Model
{
    /** @use HasFactory<EvaluationFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'docu_id', 'comment'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function docu(): BelongsTo
    {
        return $this->belongsTo(Docu::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(EvaluationField::class);
    }

    public function note(): int
    {
        $note = 0;
        foreach ($this->fields as $field) {
            $note = $note + $field->note;
        }
        return $note;
    }

    public function notes(): Collection
    {
        $res = collect();
        foreach ($this->fields as $field) {
            $res = $res->push($field->note);
        }
        return $res;
    }


    public function getNote(EvaluationCriterion $criterion)
    {
        return $this->fields->firstWhere('evaluation_criterion_id', $criterion->id)?->note;
    }

    public function getComment(EvaluationCriterion $criterion)
    {
        return $this->fields->firstWhere('evaluation_criterion_id', $criterion->id)?->comment;
    }

    public function getEvaluations(): array
    {
        $res = [];
        foreach ($this->fields as $evaluation_field) {
            $res[$evaluation_field->evaluation_criterion_id] = [
                'note' => $evaluation_field->note,
                'comment' => $evaluation_field->comment,
            ];
        }
        return $res;
    }

    public function maxNote(): int
    {
        return EvaluationCriterion::all()->count() * 5;
    }


    protected static function newFactory(): EvaluationFactory
    {
        return EvaluationFactory::new();
    }
}
