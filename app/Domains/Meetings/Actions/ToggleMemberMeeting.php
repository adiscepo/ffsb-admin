<?php

namespace App\Domains\Meetings\Actions;

use App\Models\User;
use App\Domains\Events\Event;
use App\Domains\Meetings\Meeting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ToggleMemberMeeting
{

    public function execute(User $user, Meeting $meeting, Collection $members)
    {
        DB::transaction(function () use ($user, $meeting, $members) {
            // Need to use the id of the models, otherwise the metadatas of the
            // eloquent models fetched from the database contains values
            // that prevent doing diff on them correctly
            $members_meeting_id = $meeting->members->collect()->pluck('id');
            $members_id = $members->pluck('id');
            $to_remove = $members_meeting_id->diff($members_id);
            $to_add = $members_id->diff($members_meeting_id);
            foreach ($to_remove as $member) {
                $meeting->members()->detach($member);
                $meeting->events()->attach(Event::create([
                    'author_id' => $user->id,
                    'type' => 'remove_member',
                    'payload' => [
                        'member_id' => $member,
                    ],
                ]));
            }
            foreach ($to_add as $member_id) {
                $meeting->members()->attach($member_id);
                $meeting->events()->attach(Event::create([
                    'author_id' => $user->id,
                    'type' => 'add_member',
                    'payload' => [
                        'member_id' => $member_id,
                    ],
                ]));
            }
        });
    }
}
