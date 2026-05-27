<?php

namespace App\Domains\Evaluations;

use App\Domains\Evaluations\Factory\EvaluationFieldFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationField extends Model
{
    /** @use HasFactory<EvaluationFieldFactory> */
    use HasFactory;

    protected $fillable = ['evaluation_id', 'evaluation_criterion_id', 'note', 'comment'];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(EvaluationCriterion::class);
    }

    protected static function newFactory(): EvaluationFieldFactory
    {
        return EvaluationFieldFactory::new();
    }
}
