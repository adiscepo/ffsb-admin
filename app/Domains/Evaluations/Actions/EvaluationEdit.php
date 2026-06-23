<?php

namespace App\Domains\Evaluations\Actions;

use App\Domains\Evaluations\Evaluation;
use App\Domains\Evaluations\EvaluationField;
use App\Domains\Events\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EvaluationEdit
{
    public function execute(User $user, Evaluation $evaluation, ?string $comment, ?array $evaluations, ?bool $draft)
    {
        DB::transaction(function () use ($user, $evaluation, $comment, $evaluations, $draft) {

            $old_draft = $evaluation->draft;

            $evaluation->update([
                'comment' => $comment,
                'draft' => $draft,
            ]);

            if (isset($evaluations)) {
                foreach ($evaluations as $id => $criterion) {
                    EvaluationField::updateOrCreate([
                        'evaluation_id' => $evaluation->id,
                        'evaluation_criterion_id' => $id,
                    ], [
                        'note' => $criterion['note'] ?? 0,
                        'comment' => $criterion['comment'] ?? '',
                    ]);
                }
            }

            $event_edit = Event::create([
                'author_id' => $user->id,
                'type' => 'edit_evaluation',
                'payload' => [
                    'evaluation_id' => $evaluation->id,
                ],
            ]);

            if ($old_draft != $draft and !$draft) {
                $event_publish = Event::create([
                    'author_id' => $user->id,
                    'type' => 'published_evaluation',
                    'payload' => [
                        'evaluation_id' => $evaluation->id,
                    ],
                ]);
                $user->events()->attach($event_publish);
                $evaluation->events()->attach($event_publish);
            }

            $user->events()->attach($event_edit);
            $evaluation->events()->attach($event_edit);
        });
    }
}
