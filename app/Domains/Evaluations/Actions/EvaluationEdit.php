<?php

namespace App\Domains\Evaluations\Actions;

use App\Domains\Evaluations\Evaluation;
use App\Domains\Evaluations\EvaluationField;

class EvaluationEdit
{
    public function execute(Evaluation $evaluation, array $data): Evaluation
    {
        $evaluation->update([
            'comment' => $data['comment'],
        ]);

        if (isset($data['evaluations'])) {
            foreach ($data['evaluations'] as $id => $criterion) {
                EvaluationField::updateOrCreate([
                    'evaluation_id' => $evaluation->id,
                    'evaluation_criterion_id' => $id,
                ], [
                    'note' => $criterion['note'] ?? 0,
                    'comment' => $criterion['comment'] ?? '',
                ]);
            }
        }

        return $evaluation;
    }
}
