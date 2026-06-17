<?php

namespace App\Domains\Programs\Actions;

use App\Domains\Events\Event;
use App\Domains\Programs\Enum\ProgramEventKind;
use App\Domains\Programs\Program;
use App\Domains\Programs\ProgramEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateProgramEvent
{

    public function execute(User $user, Program $program, string $start, ProgramEventKind $kind, array $payload)
    {
        DB::transaction(function () use ($user, $program, $start, $kind, $payload) {
            $program_event = ProgramEvent::create([
                'program_id' => $program->id,
                'start' => $start,
                'kind' => $kind,
                'payload' => $payload,
            ]);

            $event_create = Event::create([
                'author_id' => $user->id,
                'type' => 'add_event_program',
                'payload' => [
                    'event_id' => $program_event->id,
                ]
            ]);

            $user->events()->attach($event_create);
            $program->events()->attach($event_create);
        });
    }
}
