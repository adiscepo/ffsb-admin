<?php

namespace App\Domains\Roles\Actions;

use App\Models\User;
use App\Domains\Events\Event;
use App\Domains\Roles\Role;
use Illuminate\Support\Facades\DB;

class AssignRole
{

    public function execute(User $user, Role $role)
    {
        DB::transaction(function () use ($user, $role) {
            $user->roles()->attach($role);

            $event_assign_role = Event::create([
                'author_id' => $user->id,
                'type' =>  'assign_role',
                'payload' => [
                    'role_id' => $role->id,
                ]
            ]);

            $user->events()->attach($event_assign_role);
        });
    }
}
