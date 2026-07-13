<?php

namespace App\Domains\Contacts\Traits;

use App\Domains\Contacts\Contact;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Contactable
{
    public function contacts(): MorphToMany
    {
        return $this->morphToMany(Contact::class, 'contactable');
    }
}
