<?php

namespace App\Domains\Kanban\Policies;

use App\Domains\Kanban\Kanban;
use App\Models\User;

class KanbanPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user): bool
    {
        // TODO: implements when roles are working
        return true;
    }

    public function update(User $user, Kanban $kanban): bool
    {
        return $kanban->user->id == $user->id;
    }
}
