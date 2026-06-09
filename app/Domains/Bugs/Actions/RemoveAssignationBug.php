<?php

namespace App\Domains\Bugs\Actions;

use App\Models\User;
use App\Domains\Bugs\Bug;

class RemoveAssignationBug
{

    public function execute(Bug $bug)
    {
        $bug->update(['assigned_to' => null]);
    }
}
