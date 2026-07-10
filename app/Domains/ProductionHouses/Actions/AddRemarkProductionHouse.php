<?php

namespace App\Domains\ProductionHouses\Actions;

use App\Models\User;
use App\Domains\Events\Event;
use App\Domains\ProductionHouses\ProductionHouse;
use Illuminate\Support\Facades\DB;

class AddRemarkProductionHouse
{

    public function execute(User $user, ProductionHouse $production_house, string $remark)
    {
        DB::transaction(function () use ($user, $production_house, $remark) {
            $event_comment = Event::create([
                'author_id' => $user->id,
                'type' =>  'comment',
                'payload' => [
                    'content' => $remark,
                ]
            ]);

            $production_house->events()->attach($event_comment);
        });
    }
}
