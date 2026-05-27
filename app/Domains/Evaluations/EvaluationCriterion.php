<?php

namespace App\Domains\Evaluations;

use App\Domains\Evaluations\Factory\EvaluationCriterionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationCriterion extends Model
{
    /** @use HasFactory<EvaluationCriterionFactory> */
    use HasFactory;

    protected static function newFactory(): EvaluationCriterionFactory
    {
        return EvaluationCriterionFactory::new();
    }
}
