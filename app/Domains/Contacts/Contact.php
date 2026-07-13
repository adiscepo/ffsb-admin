<?php

namespace App\Domains\Contacts;

use App\Domains\Contacts\Factory\ContactFactory;
use App\Domains\Tags\Traits\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Contact extends Model
{
    use HasFactory, Taggable;
    public $timestamps = false;
    public $fillable = ['name', 'contact_phone', 'contact_email', 'remark'];

    /**
     * Return all the tags for a specific model
     *
     * @param string $model (Model::class)
     * @return Collection
     */
    public static function for($model): Collection
    {
        return Contact::where('model', $model)->orWhere('model', null)?->groupBy('name')->select(['id', 'name'])->get();
    }

    protected static function newFactory(): ContactFactory
    {
        return ContactFactory::new();
    }
}
