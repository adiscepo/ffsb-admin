<?php

namespace App\Domains\Bugs;

use App\Domains\Bugs\Factory\BugFactory;
use App\Domains\Events\Traits\Eventable;
use App\Domains\Tags\Traits\Taggable;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bug extends Model
{
    use HasFactory;
    use Eventable, Taggable;

    protected $fillable = ['title', 'description', 'user_id', 'files_upload', 'assigned_to', 'open'];

    protected $casts = ['files_upload' => 'array'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignation(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // public function events(): MorphToMany
    // {
    //     return $this->morphToMany(Event::class, 'eventable');
    // }

    // public function statuses(): MorphToMany
    // {
    //     return $this->morphToMany(Status::class, 'statusable');
    // }

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
        $this->attributes['files_upload'] =  json_encode($files);
    }

    public function hasFiles(): bool
    {
        if (isset($this->files_upload))
            return count($this->files_upload) > 0;
        return false;
    }

    /**
     * Set the factory (because use a non-common path)
     *
     */
    protected static function newFactory(): BugFactory
    {
        return BugFactory::new();
    }
};
