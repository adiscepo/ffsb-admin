<?php

namespace App\Domains\Bugs\Actions;

use App\Models\User;
use App\Domains\Bugs\Bug;
use App\Domains\Events\Event;
use Illuminate\Support\Facades\Auth;

class RemoveAssignationBug
{

    public function execute(Bug $bug)
    {
        $user = $bug->assignation;
        $bug->update(['assigned_to' => null]);
        $bug->events()->attach(Event::create([
            'author_id' => Auth::user()->id,
            'type' => 'remove_assignation',
            'payload' => ['user' => $user->id],
        ]));
    }
}
