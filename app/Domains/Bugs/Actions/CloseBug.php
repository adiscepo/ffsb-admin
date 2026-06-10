<?php

namespace App\Domains\Bugs\Actions;

use App\Models\User;
use App\Domains\Bugs\Bug;
use App\Domains\Events\Event;
use App\Models\Tag;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

class CloseBug
{

    public function execute(User $user, Bug $bug)
    {
        DB::transaction(function () use ($user, $bug) {
            $bug->update([
                'open' => false
            ]);

            $event_create = Event::create([
                'author_id' => $user->id,
                'type' =>  'close',
            ]);

            $bug->events()->attach($event_create);

            // Previous version, with status for 'open', 'resolved', etc.
            // But it was too explicit, a simple boolean open/closed is enough
            // (what was I thinking ? Recreating git ?)

            // // Attach status 'Ouvert' to the bug
            // $status_open = Status::where([
            //     'name' => 'Ouvert',
            //     'model' => Bug::class,
            // ])->get();
            // $bug->statuses()->attach($status_open);
        });
    }
}
