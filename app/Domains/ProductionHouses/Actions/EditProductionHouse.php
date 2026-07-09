<?php

namespace App\Domains\ProductionHouses\Actions;

use App\Domains\Docus\Enum\DocuLang;
use App\Models\User;
use App\Domains\Events\Event;
use App\Domains\ProductionHouses\ProductionHouse;
use Illuminate\Support\Facades\DB;

class EditProductionHouse
{
    public function execute(User $user, ProductionHouse $production_house, string $name, ?DocuLang $lang, ?string $website, ?string $contact_email, ?string $contact_phone, ?string $remark)
    {
        DB::transaction(function () use ($user, $production_house, $name, $lang, $website, $contact_email, $contact_phone, $remark) {
            $production_house->update([
                'name' => $name,
                'lang' => $lang?->value,
                'website' => $website,
                'contact_phone' => $contact_phone,
                'contact_email' => $contact_email,
                'remark' => $remark,
                'user_id' => $user->id,
            ]);

            $event_edit = Event::create([
                'author_id' => $user->id,
                'type' => 'edit',
            ]);

            $production_house->events()->attach($event_edit);
        });
    }
}
