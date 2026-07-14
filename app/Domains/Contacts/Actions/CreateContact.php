<?php

namespace App\Domains\Contacts\Actions;

use App\Models\User;
use App\Domains\Contacts\Contact;
use App\Domains\Contacts\Traits\Contactable;
use App\Domains\Events\Event;
use App\Domains\Events\Traits\Eventable;
use Illuminate\Support\Facades\DB;

class CreateContact
{

    public function execute(User $user, string $name, ?string $contact_phone = null, ?string $contact_email = null, ?string $remark = null)
    {
        DB::transaction(function () use ($user, $name, $contact_phone, $contact_email, $remark) {
            $contact = Contact::create([
                'user_id' => $user->id,
                'name' => $name,
                'contact_phone' => $contact_phone,
                'contact_email' => $contact_email,
                'remark' => $remark,
            ]);

            $contact_create = Event::create([
                'author_id' => $user->id,
                'type' =>  'create_contact',
                'payload' => [
                    'contact_id' => $contact->id,
                ],
            ]);

            $contact->events()->attach($contact_create);
        });
    }
}
