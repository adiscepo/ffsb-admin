@props([
    'icon' => null,
    'author' => null,
    'time' => null,
])

<li data-timeline-item="">
    <div data-timeline-icon="" class="p-2 rounded-full bg-zinc-200 w-fit">
        <?php if ($icon): ?>
        <flux:icon :icon="$icon" class="size-4 text-zinc-700" />
        <?php endif; ?>
    </div>
    @if ($author)
        <span class="font-bold text-zinc-700">{{ $author }}</span>
    @endif
    {{ $slot }}
    @if ($time)
        <span class="font-extralight text-sm text-zinc-400">· {{ $time }}</span>
    @endif
</li>
