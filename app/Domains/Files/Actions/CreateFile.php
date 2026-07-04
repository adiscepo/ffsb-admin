<?php

namespace App\Domains\Files\Actions;

use App\Domains\Events\Event;
use App\Domains\Files\File;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateFile
{

    public function execute(User $user, string $filename, string $full_path, string $client_filename, int $size, ?string $extension = null)
    {
        DB::transaction(function () use ($user, $filename, $full_path, $client_filename, $size, $extension) {
            $file = File::create([
                'filename' => $filename,
                'full_path' => $full_path,
                'client_name' => $client_filename,
                'size' => $size,
                'extension' => $extension,
                'user_id' => $user->id,
            ]);

            $event_create = Event::create([
                'author_id' => $user->id,
                'type' => 'add_file',
                'payload' => [
                    'file_id' => $file->filename,
                ]
            ]);

            $user->events()->attach($event_create);
        });
    }
}
