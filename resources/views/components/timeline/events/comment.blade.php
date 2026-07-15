@props(['event'])

@if (isset($event->payload['removed']) && $event->payload['removed'])
    <x-message :$event class="ml-[-35pt]">
        <span class="text-zinc-500 text-sm dark:text-zinc-300 italic">Commentaire supprimé</span>
    </x-message>
@else
    <livewire:timeline.events.comment-livewire :$event />
@endif
