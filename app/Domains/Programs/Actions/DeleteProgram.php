<?php

namespace App\Domains\Programs\Actions;

use App\Domains\Events\Event;
use App\Domains\Programs\Enum\ProgramEventKind;
use App\Domains\Programs\Program;
use App\Domains\Programs\ProgramEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteProgram
{

    public function execute(User $user, Program $program)
    {
        DB::transaction(function () use ($user, $program) {

            $event_delete = Event::create([
                'author_id' => $user->id,
                'type' => 'delete_program',
                'payload' => [
                    'program_name' => $program->name,
                ]
            ]);

            $program->delete();
            $user->events()->attach($event_delete);
        });
    }
}
