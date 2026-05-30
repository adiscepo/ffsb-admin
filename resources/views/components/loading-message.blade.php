{{-- Displayed when wire:loading is enabled --}}

@props([
    'message' => 'Chargement',
])

<div wire:loading class="absolute z-10 w-full h-full bg-white">
    <div class="flex items-center justify-center h-full gap-2">

        <flux:icon.loading></flux:icon.loading>
        @if (isset($slot))
            {{ $slot }}
        @else
            <span>{{ $message }}</span>
        @endif
    </div>
</div>
