<?php

namespace App\Domains\Contacts\Actions;

use App\Models\User;
use App\Domains\Contacts\Contact;
use App\Domains\Contacts\Traits\Contactable;
use App\Domains\Events\Event;
use App\Domains\Events\Traits\Eventable;
use Illuminate\Support\Facades\DB;

class EditContact
{

    public function execute(User $user, Contact $contact, string $name, ?string $contact_phone = null, ?string $contact_email = null, ?string $remark = null)
    {
        DB::transaction(function () use ($user, $contact, $name, $contact_phone, $contact_email, $remark) {
            $contact->update([
                'name' => $name,
                'contact_phone' => $contact_phone,
                'contact_email' => $contact_email,
                'remark' => $remark,
            ]);

            $contact_edit = Event::create([
                'author_id' => $user->id,
                'type' =>  'edit_contact',
                'payload' => [
                    'contact_id' => $contact->id,
                ],
            ]);

            $contact->events()->attach($contact_edit);
        });
    }
}
