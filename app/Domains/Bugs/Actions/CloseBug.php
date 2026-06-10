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
        });
    }
}
