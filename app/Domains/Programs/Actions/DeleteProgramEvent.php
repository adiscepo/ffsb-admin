<?php

namespace App\Domains\Programs\Actions;

use App\Domains\Events\Event;
use App\Domains\Programs\Enum\ProgramEventKind;
use App\Domains\Programs\Program;
use App\Domains\Programs\ProgramEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteProgramEvent
{

    public function execute(User $user, ProgramEvent $program_event)
    {
        DB::transaction(function () use ($user, $program_event) {
            $program = $program_event->program;
            ProgramEvent::find($program_event->id)->delete();

            $event_delete = Event::create([
                'author_id' => $user->id,
                'type' => 'delete_event_program',
                'payload' => [
                    'event_id' => $program_event->id,
                ]
            ]);

            $program->events()->attach($event_delete);
        });
    }
}
