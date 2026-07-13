@use('App\Models\User')

<livewire:message :$event class="ml-[-35pt]" :value="$event->payload['content']">
    <livewire:slot name="header">
        <span class="text-zinc-800 dark:text-zinc-300 font-medium">{{ $event->author->name }}</span>
        • {{ $event->created_at->diffForHumans() }}
    </livewire:slot>
    <p class="text-zinc-800 dark:text-zinc-200">{!! nl2br($event->payload['content']) !!}</p>
</livewire:message>
