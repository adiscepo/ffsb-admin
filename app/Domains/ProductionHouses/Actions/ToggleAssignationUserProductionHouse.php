<?php

namespace App\Domains\ProductionHouses\Actions;

use App\Domains\ProductionHouses\ProductionHouse;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Domains\ProductionHouses\Actions\AssignUserProductionHouse;
use App\Domains\ProductionHouses\Actions\UnassignUserProductionHouse;

class ToggleAssignationUserProductionHouse
{
    public function execute(User $author, ProductionHouse $production_house, User $user)
    {
        DB::transaction(function () use ($author, $production_house, $user) {
            if ($production_house->assignee->contains($user)) {
                new UnassignUserProductionHouse()->execute($author, $production_house, $user);
            } else {
                new AssignUserProductionHouse()->execute($author, $production_house, $user);
            }
        });
    }
}
