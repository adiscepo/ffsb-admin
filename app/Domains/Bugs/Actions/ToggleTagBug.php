<?php

namespace App\Domains\Bugs\Actions;

use App\Models\User;
use App\Domains\Bugs\Bug;
use App\Domains\Events\Event;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ToggleTagBug
{

    public function execute(Bug $bug, Collection $tags)
    {
        DB::transaction(function () use ($bug, $tags) {
            // Need to use the id of the models, otherwise the metadatas of the
            // eloquent models fetched from the database contains values
            // that prevent doing diff on them correctly
            $bug_tags_id = $bug->tags->collect()->pluck('id');
            $tags_id = $tags->pluck('id');
            $to_remove = $bug_tags_id->diff($tags_id);
            $to_add = $tags_id->diff($bug_tags_id);
            foreach ($to_remove as $tag_id) {
                $bug->tags()->detach($tag_id);
                $bug->events()->attach(Event::create([
                    'author_id' => Auth::user()->id,
                    'type' => 'remove_tag',
                    'payload' => [
                        'tag_id' => $tag_id,
                    ],
                ]));
            }
            foreach ($to_add as $tag_id) {
                $bug->tags()->attach($tag_id);
                $bug->events()->attach(Event::create([
                    'author_id' => Auth::user()->id,
                    'type' => 'add_tag',
                    'payload' => [
                        'tag_id' => $tag_id,
                    ],
                ]));
            }
        });
    }
}
