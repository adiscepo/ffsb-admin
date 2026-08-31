<?php

namespace App\Policies;

use App\Domains\Kanban\Kanban;
use App\Models\User;

class KanbanCardPolicy
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
}
