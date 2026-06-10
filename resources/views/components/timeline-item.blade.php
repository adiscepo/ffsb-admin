@props([
    'header' => null,
    'icon' => null,
    'author' => null,
    'time' => null,
    'color' => 'zinc',
])

<li data-timeline-item="">
    <div data-timeline-icon="" class="p-2 rounded-full bg-zinc-000 border-{{ $color }}-900 border bg-white w-fit">
        @if ($icon)
            <flux:icon :icon="$icon" variant="micro" class="size-4 text-{{ $color }}-500" />
        @endif
    </div>
    <div class="space-y-1">
        <div class="flex flex-col">
            @if (isset($header))
                {{ $header }}
            @elseif (isset($author))
                <span class="font-medium text-zinc-800"> {{ $author }}</span>
            @endif
            @if ($time)
                <span class="font-extralight text-xs text-zinc-400"> {{ $time }}</span>
            @endif
        </div>
        <div>
            {{ $slot }}
        </div>
    </div>
</li>
