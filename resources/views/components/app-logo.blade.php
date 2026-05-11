@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="FFSB Admin" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent text-accent-foreground">
            <x-app-logo-icon class="size-6 fill-current dark:text-black" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="FFSB Admin" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:brand>
@endif
