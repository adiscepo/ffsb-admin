<?php

namespace App\Domains\Files\Actions;

use App\Domains\Events\Event;
use App\Domains\Files\File;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteFile
{
    public function execute(User $user, string $filename)
    {
        DB::transaction(function () use ($user, $filename) {

            $file = File::findOrFail($filename);

            $event_delete = Event::create([
                'author_id' => $user->id,
                'type' => 'delete_file',
                'payload' => [
                    'filename' => $file->client_filename,
                ]
            ]);

            $file->delete();

            $user->events()->attach($event_delete);
        });
    }
}
