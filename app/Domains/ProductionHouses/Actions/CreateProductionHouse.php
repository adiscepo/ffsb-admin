<?php

namespace App\Domains\ProductionHouses\Actions;

use App\Domains\Docus\Enum\DocuLang;
use App\Models\User;
use App\Domains\Events\Event;
use App\Domains\ProductionHouses\ProductionHouse;
use Illuminate\Support\Facades\DB;

class CreateProductionHouse
{
    public function execute(User $user, string $name, ?DocuLang $lang = null, ?string $website = null, ?string $contact_email = null, ?string $contact_phone = null, ?string $remark = null)
    {
        DB::transaction(function () use ($user, $name, $lang, $website, $contact_email, $contact_phone, $remark) {
            $production_house = ProductionHouse::create([
                'name' => $name,
                'lang' => $lang?->value,
                'website' => $website,
                'contact_phone' => $contact_phone,
                'contact_email' => $contact_email,
                'remark' => $remark,
            ]);

            $event_create = Event::create([
                'author_id' => $user->id,
                'type' => 'create',
            ]);

            $production_house->events()->attach($event_create);
        });
    }
}
