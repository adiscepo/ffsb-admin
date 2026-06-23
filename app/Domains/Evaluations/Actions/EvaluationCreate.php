<?php

namespace App\Domains\Evaluations\Actions;

use App\Domains\Evaluations\Evaluation;
use App\Domains\Evaluations\EvaluationField;
use App\Domains\Docus\Docu;
use App\Domains\Events\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EvaluationCreate
{

    public function execute(User $user, Docu $docu, ?string $comment, ?array $evaluations, ?bool $draft)
    {
        DB::transaction(function () use ($user, $docu, $comment, $evaluations, $draft) {

            $evaluation = Evaluation::create([
                'user_id' => $user->id,
                'docu_id' => $docu->id,
                'comment' => $comment ?? '',
                'draft' => $draft ?? true,
            ]);

            if (isset($evaluations)) {
                foreach ($evaluations as $id => $evaluation) {
                    EvaluationField::create([
                        'evaluation_id' => $evaluation->id,
                        'evaluation_criterion_id' => $id,
                        'note' => $evaluation['note'],
                        'comment' => $evaluation['comment'],
                    ]);
                }
            }

            $event_create = Event::create([
                'author_id' => $user->id,
                'type' => 'add_evaluation',
            ]);

            $user->events()->attach($event_create);
            $evaluation->events()->attach($event_create);
        });
    }
}
