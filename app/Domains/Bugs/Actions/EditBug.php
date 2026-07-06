<?php

namespace App\Domains\Bugs\Actions;

use App\Domains\Bugs\Bug;
use App\Domains\Tags\Tag;
use Illuminate\Support\Facades\DB;

class EditBug
{

    public function execute(Bug $bug, ?array $data = null)
    {
        DB::transaction(function () use ($bug, $data) {
            $bug->update($data);

            if (isset($data['tags'])) {
                foreach ($data['tags'] as $tag) {
                    $tag = Tag::find($tag, 'id');
                    if ($tag) {
                        $bug->tags()->attach($tag);
                    }
                }
            }
        });
    }
}
