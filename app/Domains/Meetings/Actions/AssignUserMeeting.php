<?php

namespace App\Domains\Meetings\Actions;

use App\Domains\Events\Event;
use App\Domains\Meetings\Meeting;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AssignUserMeeting
{

    public function execute(User $user, Meeting $meeting, User $member)
    {
        DB::transaction(function () use ($user, $meeting, $member) {
            $meeting->members()->attach($member);

            $event_create = Event::create([
                'author_id' => $user->id,
                'type' => 'add_member',
                'payload' => [
                    'member_id' => $member->id,
                ]
            ]);

            $meeting->events()->attach($event_create);
        });
    }
}
