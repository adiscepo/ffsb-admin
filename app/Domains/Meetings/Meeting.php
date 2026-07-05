<?php

namespace App\Domains\Meetings;

use App\Domains\Events\Event;
use App\Domains\Events\Traits\Eventable;
use App\Domains\Files\File;
use App\Domains\Meetings\Factory\MeetingFactory;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Meeting extends Model
{
    use HasFactory, Eventable;

    protected $fillable = ['user_id', 'name', 'datetime', 'location', 'description', 'files_upload'];

    protected $casts = [
        'files_upload' => 'array',
        'datetime' => 'immutable_datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'meeting_user', 'meeting_id', 'user_id');
    }

    /**
     *  Accessor to uploaded files
     */
    public function getUploadedFiles()
    {
        // return json_decode($this->attributes['files_upload'], true);
        return $this->files_upload;
    }

    /**
     *  Mutator to uploaded files
     */
    public function setUploadedFiles(array $files)
    {
        $this->attributes['files_upload'] = json_encode($files);
        $this->save();
    }

    /**
     *  Mutator to uploaded files
     */
    public function addUploadedFiles(array $files)
    {
        foreach ($files as $file) {
            $this->events()->attach(Event::create([
                'author_id' => Auth::user()->id,
                'type' => 'add_file',
                'payload' => [
                    'file_id' => File::findOrFail($file)->filename,
                ]
            ]));
        }
        $this->attributes['files_upload'] = json_encode(array_merge($this->files_upload ?? [], $files));
        $this->save();
    }

    public function removeUploadedFile(string $filename, string $client_name)
    {
        $collection = collect($this->files_upload);
        $collection = $collection->reject(function ($item) use ($filename) {
            return $item === $filename;
        });
        $this->attributes['files_upload'] = json_encode($collection->toArray());
        $this->events()->attach(Event::create([
            'author_id' => Auth::user()->id,
            'type' => 'remove_file',
            'payload' => [
                'client_name' => $client_name,
            ],
        ]));
        $this->save();
    }

    public function hasFiles(): bool
    {
        if (isset($this->files_upload))
            return count($this->files_upload) > 0;
        return false;
    }

    protected static function newFactory(): MeetingFactory
    {
        return MeetingFactory::new();
    }
}
