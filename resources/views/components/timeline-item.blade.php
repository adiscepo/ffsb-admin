@props([
    'header' => null,
    'icon' => 'question-mark-circle',
    'author' => null,
    'time' => null,
    'color' => 'zinc',
])

<li data-timeline-item="">
    <div data-timeline-icon=""
        class="p-2 rounded-full bg-zinc-000 dark:border-{{ $color }}-100  }} border bg-white dark:bg-zinc-800 w-fit">
        @if ($icon)
            <flux:icon :icon="$icon" variant="micro" class="size-4 text-{{ $color }}-500" />
        @endif
    </div>
    <div class="flex flex-wrap gap-1 md:items-center">
        @if (isset($header))
            {{ $header }}
        @elseif (isset($author))
            <span class="font-medium text-zinc-800 dark:text-zinc-200"> {{ $author }}</span>
        @endif
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            <span>Un évènement à eu lieu mais il n'est pas encore codé..</span>
        @endif
        @if ($time)
            <span class="font-extralight text-xs text-zinc-400"> {{ $time }}</span>
        @endif
    </div>
</li>
