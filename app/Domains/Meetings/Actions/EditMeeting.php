<?php

namespace App\Domains\Meetings\Actions;

use App\Models\User;
use App\Domains\Events\Event;
use App\Domains\Meetings\Meeting;
use Illuminate\Support\Facades\DB;

class EditMeeting
{

    public function execute(User $user, Meeting $meeting, string $name, string $datetime, string $location, string $description)
    {
        DB::transaction(function () use ($user, $meeting, $name, $datetime, $location, $description) {
            $meeting->update([
                'name' => $name,
                'datetime' => $datetime,
                'location' => $location,
                'description' => $description,
            ]);

            $event_edit = Event::create([
                'author_id' => $user->id,
                'type' => 'edit',
            ]);

            $meeting->events()->attach($event_edit);
        });
    }
}
