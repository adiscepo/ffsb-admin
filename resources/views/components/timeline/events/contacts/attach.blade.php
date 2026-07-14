<?php
use App\Domains\Contacts\Contact;
?>

<x-timeline-item icon="identification" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    @php
        $contact = Contact::find($event->payload['contact_id']);
    @endphp
    <p>a attaché <span class="underline">{{ $contact->name }}</span> aux contacts</p>
</x-timeline-item>
