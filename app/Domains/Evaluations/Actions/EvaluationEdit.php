<?php

use App\Domains\Evaluations\Evaluation;
use App\Models\Docu;
use App\Models\User;

class EvaluationCreate
{
    public function execute(User $user, Docu $docu, array $data): Evaluation
    {
        $evaluation = Evaluation::update([
            'user_id' => $user->id,
            'docu_id' => $docu->id,
            'comment' => $data['comment'],
        ]);

        return $evaluation;
    }
}
