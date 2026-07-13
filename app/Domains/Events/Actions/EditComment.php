<?php

namespace App\Domains\Events\Actions;

use App\Domains\Events\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EditComment
{

    public function execute(User $user, Event $event, string $new_value)
    {
        DB::transaction(function () use ($user, $event, $new_value) {
            if ($event->type == 'comment') {
                $previous_value = $event->payload['content'];
                $event->update([
                    'payload' => [
                        'content' => $new_value,
                        'previous' => $previous_value,
                        'edited' => true,
                    ],
                ]);
            }
        });
    }
}
