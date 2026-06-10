<?php

namespace App\Domains\Bugs\Actions;

use App\Models\User;
use App\Domains\Bugs\Bug;
use App\Domains\Events\Event;
use Illuminate\Support\Facades\DB;

class CommentBug
{

    public function execute(User $user, Bug $bug, string $comment)
    {
        DB::transaction(function () use ($user, $bug, $comment) {
            $event_comment = Event::create([
                'author_id' => $user->id,
                'type' =>  'comment',
                'payload' => [
                    'content' => $comment,
                ]
            ]);

            $bug->events()->attach($event_comment);
        });
    }
}
