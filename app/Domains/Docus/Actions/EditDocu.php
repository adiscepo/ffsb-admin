<?php

namespace App\Domains\Docus\Actions;

use App\Domains\Docus\DocuLink;
use App\Models\User;
use App\Domains\Docus\Docu;
use App\Domains\Events\Event;
use App\Domains\ProductionHouses\Actions\AttachDocuProductionHouse;
use App\Domains\ProductionHouses\Actions\DetachDocuProductionHouse;
use App\Domains\ProductionHouses\ProductionHouse;
use Illuminate\Support\Facades\DB;

class EditDocu
{

    public function execute(User $user, Docu $docu, ?array $data = null)
    {
        DB::transaction(function () use ($user, $docu, $data) {
            $docu->update([
                'title' => $data['title'],
                'summary' => $data['summary'],
                'duration' => $data['duration'],
                'year' => $data['year'],
                'lang' => $data['lang'],
                'subtitles' => $data['subtitle'],
                'target' => $data['target'],
                'comment' => $data['comment'],
                'edition_year_id' => $data['edition_year_id'],
            ]);

            foreach ($data['links'] as $id => $link) {
                $docu_link = DocuLink::where("url", $link['url'])->first();
                if (!$docu->see_at->contains($docu_link)) {
                    DocuLink::create([
                        'url' => $link['url'],
                        'password' => $link['password'],
                        'deadline' => !empty($link['deadline']) ? $link['deadline'] : null,
                        'docu_id' => $docu->id,
                    ]);
                } else {
                    $docu_link->update([
                        'url' => $link['url'],
                        'password' => $link['password'],
                        'deadline' => !empty($link['deadline']) ? $link['deadline'] : null,
                        'docu_id' => $docu->id,
                    ]);
                }
            }

            $links_id = collect($data['links'])->pluck('id');
            foreach ($docu->see_at as $link) {
                if (!$links_id->contains($link->id)) {
                    // Production house was removed
                    $link->delete();
                }
            }

            foreach ($data['production_houses'] as $id => $production_house) {
                if (!$docu->from->contains($production_house)) {
                    new AttachDocuProductionHouse()->execute($user, ProductionHouse::findOrFail($production_house), $docu);
                }
            }

            foreach ($docu->from as $production_house) {
                if (!in_array($production_house->id, $data['production_houses'])) {
                    // Production house was removed
                    new DetachDocuProductionHouse()->execute($user, ProductionHouse::findOrFail($production_house), $docu);
                }
            }

            foreach ($data['fields'] as $id => $field) {
                if (!$docu->fields->contains($field)) {
                    $docu->fields()->attach($field);
                }
            }

            foreach ($docu->fields as $field) {
                if (!in_array($field->id, $data['fields'])) {
                    // Production house was removed
                    $docu->fields()->detach($field);
                }
            }

            $event_edit = Event::create([
                'author_id' => $user->id,
                'type' => 'edit',
            ]);

            $docu->events()->attach($event_edit);
        });
    }
}
