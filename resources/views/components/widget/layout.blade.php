@props([
    'trailing' => null,
    'icon' => null,
    'title',
])
<div class="border rounded-xl border-neutral-200">
    <div class="flex justify-between border-b border-b-zinc-200 px-4 py-2">
        <div class="flex items-center gap-x-2">
            @if (isset($icon))
                <flux:icon :icon="$icon" class="text-zinc-400" />
            @endif
            <h3 class="text-zinc-700 text-sm">{{ $title }}</h3>
        </div>
        @if (isset($trailing))
            {{ $trailing }}
        @endif
    </div>
    {{ $slot }}
</div>
