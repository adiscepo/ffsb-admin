<?php

namespace App\Domains\Docus\Actions;

use App\Domains\Docus\DocuLink;
use App\Models\User;
use App\Domains\Docus\Docu;
use App\Domains\Events\Event;
use Illuminate\Support\Facades\DB;

class CreateDocu
{

    public function execute(User $user, ?array $data = null)
    {
        DB::transaction(function () use ($user, $data) {
            $docu = Docu::create([
                'title' => $data['title'],
                'summary' => $data['summary'],
                'duration' => $data['duration'],
                'year' => $data['year'],
                'user_id' => $user->id,
                'lang' => $data['lang'],
                'subtitles' => $data['subtitles'],
                'target' => $data['target'],
                'comment' => $data['comment'],
                'edition_year_id' => $data['edition_year_id'],
            ]);

            foreach ($data['links'] as $id => $link) {
                DocuLink::create([
                    'url' => $link['url'],
                    'password' => $link['password'],
                    'deadline' => !empty($link['deadline']) ? $link['deadline'] : null,
                    'docu_id' => $docu->id,
                ]);
            }
            foreach ($data['production_houses'] as $id => $production_house) {
                $docu->from()->attach($production_house);
            }

            foreach ($data['fields'] as $id => $field) {
                $docu->fields()->attach($field);
            }

            $event_create = Event::create([
                'author_id' => $user->id,
                'type' => 'create',
            ]);

            $docu->events()->attach($event_create);

            // Previous version, with status for 'open', 'resolved', etc.
            // But it was too explicit, a simple boolean open/closed is enough
            // (what was I thinking ? Recreating git ?)

            // // Attach status 'Ouvert' to the bug
            // $status_open = Status::where([
            //     'name' => 'Ouvert',
            //     'model' => Bug::class,
            // ])->get();
            // $bug->statuses()->attach($status_open);
        });
    }
}
