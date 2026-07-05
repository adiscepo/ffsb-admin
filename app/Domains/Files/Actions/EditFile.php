<?php

namespace App\Domains\Files\Actions;

use App\Domains\Events\Event;
use App\Domains\Files\File;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EditFile
{
    // A user can only change the client_name of a file, the rest is specific
    // to the file and handled automatically by the server
    public function execute(User $user, File $file, string $client_name)
    {
        DB::transaction(function () use ($user, $file, $client_name) {
            $old_name = $file->client_name;
            $file->update([
                'client_name' => $client_name,
            ]);

            $event_edit = Event::create([
                'author_id' => $user->id,
                'type' => 'edit',
                'payload' => [
                    'edited_fields' => [
                        'client_name'
                    ],
                    'old' => [
                        'client_name' => $old_name,
                    ],
                    'new' => [
                        'client_name' => $client_name,
                    ],
                ]
            ]);

            $user->events()->attach($event_edit);
        });
    }
}
