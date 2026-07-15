<?php

namespace App\Domains\Events\Actions;

use App\Domains\Events\Event;
use App\Domains\Events\Traits\Eventable;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteComment
{

    public function execute(User $user, Event $comment)
    {
        DB::transaction(function () use ($user, $comment) {
            if ($comment->type == 'comment') {
                $comment->update([
                    'payload' => [
                        'removed' => true,
                    ],
                ]);
            }
        });
    }
}
