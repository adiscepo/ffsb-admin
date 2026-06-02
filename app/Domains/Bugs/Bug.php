<?php

namespace App\Domains\Bugs;

use App\Domains\Bugs\Factory\BugFactory;
use App\Models\Status;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Bug extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'author_id', 'files', 'status', 'assigned_to'];

    protected $casts = ['files_upload' => 'array'];

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function statuses(): MorphToMany
    {
        return $this->morphToMany(Status::class, 'statusable');
    }

    /**
     *  Accessor to uploaded files
     */
    public function getUploadedFiles()
    {
        return json_decode($this->attributes['files_upload'], true);
    }

    /**
     *  Mutator to uploaded files
     */
    public function setUploadedFiles(array $files)
    {
        $this->attributes['files_upload'] =  json_encode($files);
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
