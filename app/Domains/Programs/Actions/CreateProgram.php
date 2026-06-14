<?php

namespace App\Domains\Programs\Actions;

use App\Domains\Events\Event;
use App\Domains\Programs\Program;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateProgram
{

    public function execute(User $user, string $name, string $start_date, string $end_date, int $edition_year_id, ?int $version = null)
    {
        DB::transaction(function () use ($user, $name, $start_date, $end_date, $edition_year_id, $version) {
            $program = Program::create([
                'name' => $name,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'user_id' => $user->id,
                'edition_year_id' => $edition_year_id,
                'version' => $version,
            ]);

            $event_create = Event::create([
                'author_id' => $user->id,
                'type' => 'create_program',
            ]);

            $user->events()->attach($event_create);
            $program->events()->attach($event_create);
        });
    }
}
