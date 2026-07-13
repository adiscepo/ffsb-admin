<?php

namespace App\Domains\Contacts\Actions;

use App\Models\User;
use App\Domains\Contacts\Contact;
use App\Domains\Contacts\Traits\Contactable;
use App\Domains\Events\Event;
use App\Domains\Events\Traits\Eventable;
use Illuminate\Support\Facades\DB;

class AttachContact
{

    public function execute(User $user, $model, Contact $contact)
    {
        DB::transaction(function () use ($user, $model, $contact) {
            $model->contacts()->attach($contact);

            $contact_attach = Event::create([
                'author_id' => $user->id,
                'type' =>  'attach_contact',
                'payload' => [
                    'contact_id' => $contact->id,
                ]
            ]);

            if (in_array(Eventable::class, class_uses_recursive($model::class))) {
                $model->events()->attach($contact_attach);
            }
        });
    }
}
