<?php

namespace App\Domains\ProductionHouses\Actions;

use App\Domains\Events\Event;
use App\Domains\ProductionHouses\ProductionHouse;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AssignUserProductionHouse
{
    public function execute(User $author, ProductionHouse $production_house, User $user)
    {
        DB::transaction(function () use ($author, $production_house, $user) {
            $production_house->assignee()->attach($user);
            $event_assign = Event::create([
                'author_id' => $author->id,
                'type' => 'assign_production_house',
                'payload' => [
                    'assignee_id' => $user->id,
                ]
            ]);
            error_log($user->name . " assigned to " . $production_house->name);

            $production_house->events()->attach($event_assign);
            $user->events()->attach($event_assign);
        });
    }
}
