<?php

namespace App\Domains\Events\Actions;

use App\Domains\Events\Event;
use App\Domains\Events\Traits\Eventable;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateComment
{

    public function execute(User $user, $model, string $comment)
    {
        DB::transaction(function () use ($user, $model, $comment) {
            $eventable = in_array(Eventable::class, class_uses_recursive($model::class));
            if (!$eventable) {
                throw new Exception($model::class . " must implement the trait Eventable in order to use comments", 1);
            }
            $comment = Event::create([
                'author_id' => $user->id,
                'type' => 'comment',
                'payload' => [
                    'content' => htmlspecialchars($comment)
                ],
            ]);
            $model->events()->attach($comment);
        });
    }
}
