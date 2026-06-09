<?php

namespace App\Domains\Bugs\Actions;

use App\Models\User;
use App\Domains\Bugs\Bug;
use App\Domains\Events\Event;
use Illuminate\Support\Facades\Auth;

class AssignBugToUser
{

    public function execute(User $user, Bug $bug)
    {
        $bug->update(['assigned_to' => $user->id]);
        $bug->events()->attach(Event::create([
            'author_id' => Auth::user()->id,
            'type' => 'assignation',
            'payload' => ['assigned_to' => $user->id],
        ]));
    }
}
