<?php

namespace App\Domains\Programs\Actions;

use App\Domains\Events\Event;
use App\Domains\Programs\Enum\ProgramEventKind;
use App\Domains\Programs\Program;
use App\Domains\Programs\ProgramEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EditProgramEvent
{

    public function execute(User $user, ProgramEvent $program_event, string $start, array $payload)
    {
        DB::transaction(function () use ($user, $program_event, $start, $payload) {
            $program = $program_event->program;

            $event_edit = Event::create([
                'author_id' => $user->id,
                'type' => 'edit_event_program',
                'payload' => [
                    'event_id' => $program_event->id,
                    'previous_start' => $program_event->start,
                    'new_start' => $start,
                    'previous_payload' => $program_event->payload,
                    'new_payload' => $payload,
                ]
            ]);

            $program_event->update([
                'start' => $start,
                'payload' => $payload,
            ]);

            $program->events()->attach($event_edit);
        });
    }
}
