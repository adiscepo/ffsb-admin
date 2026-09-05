<?php

namespace App\Domains\Kanban\Policies;

use App\Domains\Kanban\KanbanCard;
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

    public function comment(User $user, KanbanCard $kanban_card): bool
    {
        return $kanban_card->assignee->contains($user->id);
    }

    public function edit(User $user, KanbanCard $kanban_card): bool
    {
        return true;
    }
}
