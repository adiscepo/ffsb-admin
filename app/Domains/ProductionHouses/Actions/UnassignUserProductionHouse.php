<?php

namespace App\Domains\ProductionHouses\Actions;

use App\Domains\Events\Event;
use App\Domains\ProductionHouses\ProductionHouse;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UnassignUserProductionHouse
{
    public function execute(User $author, ProductionHouse $production_house, User $user)
    {
        DB::transaction(function () use ($author, $production_house, $user) {
            $production_house->assignee()->detach($user);
            $event_unassign = Event::create([
                'author_id' => $author->id,
                'type' => 'unassign_production_house',
                'payload' => [
                    'assignee_id' => $user->id,
                ]
            ]);

            error_log($user->name . " unassigned to " . $production_house->name);
            $production_house->events()->attach($event_unassign);
            $user->events()->attach($event_unassign);
        });
    }
}
