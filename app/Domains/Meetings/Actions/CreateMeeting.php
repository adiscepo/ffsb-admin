<?php

namespace App\Domains\Meetings\Actions;

use App\Domains\Events\Event;
use App\Domains\Meetings\Meeting;
use App\Models\User;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class CreateMeeting
{

    public function execute(User $user, string $name, string $datetime, string $location, string $description, ?array $files_upload = null)
    {
        DB::transaction(function () use ($user, $name, $datetime, $location, $description, $files_upload) {
            $meeting = Meeting::create([
                'user_id' => $user->id,
                'name' => $name,
                'datetime' => $datetime,
                'location' => $location,
                'description' => $description,
                'files_upload' => $files_upload,
            ]);


            $event_create = Event::create([
                'author_id' => $user->id,
                'type' => 'create',
            ]);

            $user->events()->attach($event_create);
            $meeting->events()->attach($event_create);
        });
    }
}
