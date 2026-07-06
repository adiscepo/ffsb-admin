@props([
    'sidebar' => false,
])

@if ($sidebar)
    {{-- <flux:sidebar.brand name="" {{ $attributes }}> --}}
    <div
        class="flex items-center gap-x-2 in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:px-3 px-1 py-1">
        <div name="logo"
            class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent text-accent-foreground">
            <x-app-logo-icon class="size-6 fill-current dark:text-black" />
        </div>
        <div
            class="flex-col justify-around in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:flex hidden">
            <span class="text-sm font-bold text-zinc-900 dark:text-zinc-50">FFSB</span>
            <span class="text-xs text-zinc-500 dark:text-zinc-300">Admin</span>
        </div>
        {{-- </flux:sidebar.brand> --}}
    </div>
@else
    <flux:brand name="FFSB Admin" {{ $attributes }}>
        <x-slot name="logo"
            class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:brand>
@endif
