<?php

namespace App\Domains\ProductionHouses\Actions;

use App\Domains\Docus\Docu;
use App\Domains\Events\Event;
use App\Domains\ProductionHouses\ProductionHouse;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AttachDocuProductionHouse
{
    public function execute(User $author, ProductionHouse $production_house, Docu $docu)
    {
        DB::transaction(function () use ($author, $production_house, $docu) {
            $docu->from()->attach($production_house);

            $event_assign = Event::create([
                'author_id' => $author->id,
                'type' => 'attach_docu_production_house',
                'payload' => [
                    'docu_id' => $docu->id,
                ]
            ]);

            $production_house->events()->attach($event_assign);
            $docu->events()->attach($event_assign);
        });
    }
}
