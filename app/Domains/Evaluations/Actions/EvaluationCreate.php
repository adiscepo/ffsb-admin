<?php

namespace App\Domains\Evaluations\Actions;

use App\Domains\Evaluations\Evaluation;
use App\Domains\Evaluations\EvaluationField;
use App\Models\Docu;
use App\Models\User;

class EvaluationCreate
{

    public function execute(User $user, Docu $docu, ?array $data = null): Evaluation
    {
        $evaluation = Evaluation::create([
            'user_id' => $user->id,
            'docu_id' => $docu->id,
            'comment' => $data['comment'] ?? '',
        ]);

        if (isset($data['evaluations'])) {
            foreach ($data['evaluations'] as $id => $evaluation) {
                EvaluationField::create([
                    'evaluation_id' => $evaluation->id,
                    'evaluation_criterion_id' => $id,
                    'note' => $evaluation['note'],
                    'comment' => $evaluation['comment'],
                ]);
            }
        }

        return $evaluation;
    }
}
