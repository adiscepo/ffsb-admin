<?php

namespace App\Domains\Bugs\Actions;

use App\Models\User;
use App\Domains\Bugs\Bug;
use App\Models\Tag;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

class CreateBug
{

    public function execute(User $user, ?array $data = null)
    {
        DB::transaction(function () use ($user, $data) {
            $bug = Bug::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'files_upload' => $data['files_upload'],
                'open' => true
            ]);

            if (isset($data['tags'])) {
                foreach ($data['tags'] as $tag) {
                    $tag = Tag::find($tag, 'id');
                    if ($tag) {
                        $bug->tags()->attach($tag);
                    }
                }
            }
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
