<?php

namespace App\Domains\Programs\Actions;

use App\Domains\Events\Event;
use App\Domains\Programs\ProgramEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MoveProgramEvent
{

    public function execute(User $user, ProgramEvent $program_event, string $start)
    {
        DB::transaction(function () use ($user, $program_event, $start) {
            $program = $program_event->program;

            $event_move = Event::create([
                'author_id' => $user->id,
                'type' => 'move_event_program',
                'payload' => [
                    'event_id' => $program_event->id,
                    'from' => $program_event->start,
                    'to' => $start,
                ]
            ]);

            $program_event->update([
                'start' => $start,
            ]);

            $program->events()->attach($event_move);
        });
    }
}
