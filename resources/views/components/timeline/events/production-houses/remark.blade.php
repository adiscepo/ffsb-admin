@use('App\Models\User')

<x-message :user="$event->author" class="ml-[-35pt]">
    <x-slot:header>
        <span class="text-zinc-800 dark:text-zinc-300 font-medium">{{ $event->author->name }}</span>
        • {{ $event->created_at->diffForHumans() }}
    </x-slot>
    <p class="text-zinc-800 dark:text-zinc-200">{!! nl2br($event->payload['content']) !!}</p>
</x-message>
