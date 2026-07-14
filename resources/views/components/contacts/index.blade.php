<?php

use Livewire\Component;

new class extends Component {};
?>
@props(['contacts'])

<div class="flex flex-col px-5 text-sm text-zinc-500 gap-y-0.5">
    @foreach ($contacts as $contact)
        <flux:modal.trigger :name="'contact-' . $contact->id">
            <div class="flex gap-x-2 items-center cursor-pointer">
                <flux:avatar :name="$contact->name" color="auto" size="xs" circle></flux:avatar>
                <span>
                    {{ $contact->name }}
                    @foreach ($contact->tags as $tag)
                        <flux:badge :color="$tag->color">{{ $tag->name }}</flux:badge>
                    @endforeach
                </span>
            </div>
        </flux:modal.trigger>
        <flux:modal :name="'contact-' . $contact->id">
            <livewire:contacts.info :contact="$contact" />
        </flux:modal>
    @endforeach
</div>
