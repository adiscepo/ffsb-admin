@use('App\Models\User')

@props([
    'icon' => 'user',
    'event',
])

<li data-timeline-item="">
    <div data-timeline-icon="" class="p-2 rounded-full bg-zinc-100 w-fit">
        <flux:icon :icon="$lookup_icons[$event->type]['icon']"
            class="size-4 text-{{ $lookup_icons[$event->type]['color'] }}-500" variant="micro" />
    </div>
    <div class="flex flex-col">
        <div class="flex items-center gap-x-2">
            <span class="font-medium text-zinc-700">{{ $event->author->name }}</span>
            {{-- {{ $slot }} --}}
            <span class="font-bold text-zinc-400">•</span>
            <span class="font-extralight text-sm text-zinc-400">{{ $event->created_at->diffForHumans() }}</span>
        </div>
        <div class="text-zinc-600 font-normal">

            @switch($event->type)
                @case('assignation')
                    A assigné {{ User::find($event->payload['assigned_to'])->name }} au bug.
                @break

                @case('remove_assignation')
                    A désassigné {{ User::find($event->payload['user'])->name }} au bug.
                @break

                @default
            @endswitch
        </div>
    </div>
</li>
