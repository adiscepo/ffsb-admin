<?php

namespace App\Domains\Statuses\Actions;

use App\Domains\Events\Event;
use App\Domains\Events\Traits\Eventable;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ToggleStatus
{

    public function execute(User $user, $statusable, Collection $statuses)
    {
        DB::transaction(function () use ($user, $statusable, $statuses) {
            // Need to use the id of the models, otherwise the metadatas of the
            // eloquent models fetched from the database contains values
            // that prevent doing diff on them correctly
            $bug_statuses_id = $statusable->statuses->collect()->pluck('id');
            $statuses_id = $statuses->pluck('id');
            $to_remove = $bug_statuses_id->diff($statuses_id);
            $to_add = $statuses_id->diff($bug_statuses_id);
            // Check if the model use the eventable's trait (used to create the
            // timelines) if yes, the events about add/remove of the tags are
            // added to the model
            $eventable = in_array(Eventable::class, class_uses_recursive($statusable::class));
            foreach ($to_remove as $status_id) {
                $statusable->statuses()->detach($status_id);
                if ($eventable) {
                    $statusable->events()->attach(Event::create([
                        'author_id' => $user->id,
                        'type' => 'remove_status',
                        'payload' => [
                            'status_id' => $status_id,
                        ],
                    ]));
                }
            }
            foreach ($to_add as $status_id) {
                $statusable->statuses()->attach($status_id);
                if ($eventable) {
                    $statusable->events()->attach(Event::create([
                        'author_id' => $user->id,
                        'type' => 'add_status',
                        'payload' => [
                            'status_id' => $status_id,
                        ],
                    ]));
                }
            }
        });
    }
}
