<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

        <x-app-logo href="{{ route('dashboard') }}" wire:navigate />

        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                wire:navigate>
                {{ __('Tableau de bord') }}
            </flux:sidebar.item>
            @if (Route::has('docus'))
                <flux:sidebar.item icon="film" :href="route('docus')" :current="request()->routeIs('docus')"
                    wire:navigate>
                    {{ __('Documentaires') }}
                </flux:sidebar.item>
            @endif
            @if (Route::has('evaluations'))
                <flux:sidebar.item icon="document-text" :href="route('evaluations')"
                    :current="request()->routeIs('evaluations')" wire:navigate>
                    {{ __('Evaluations') }}
                </flux:sidebar.item>
            @endif
            @if (Route::has('subsides'))
                <flux:sidebar.item icon="building-office-2" :href="route('subsides')"
                    :current="request()->routeIs('subsides')" wire:navigate>
                    {{ __('Subsides') }}
                </flux:sidebar.item>
            @endif
            @if (Route::has('expenses'))
                <flux:sidebar.item icon="banknotes" :href="route('expenses')" :current="request()->routeIs('expenses')"
                    wire:navigate>
                    {{ __('Dépenses') }}
                </flux:sidebar.item>
            @endif

        </flux:navbar>

        <flux:spacer />

        <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
            <flux:tooltip :content="__('Search')" position="bottom">
                <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#"
                    :label="__('Search')" />
            </flux:tooltip>
            {{-- <flux:tooltip :content="__('Repository')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="zanzibar-git-2"
                        href="https://github.com/laravel/livewire-starter-kit"
                        target="_blank"
                        :label="__('Repository')"
                    />
                </flux:tooltip>
                <flux:tooltip :content="__('Documentation')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="book-open-text"
                        href="https://laravel.com/docs/starter-kits#livewire"
                        target="_blank"
                        :label="__('Documentation')"
                    />
                </flux:tooltip> --}}
        </flux:navbar>

        <x-desktop-user-menu />
    </flux:header>

    <!-- Mobile Menu -->
    <flux:sidebar collapsible="mobile" sticky
        class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse
                class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Pages')" class="grid">
                <flux:sidebar.item icon="home" :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Tableau de bord') }}
                </flux:sidebar.item>
                @if (Route::has('docus'))
                    <flux:sidebar.item icon="film" :href="route('docus')" :current="request()->routeIs('docus')"
                        wire:navigate>
                        {{ __('Documentaires') }}
                    </flux:sidebar.item>
                @endif
                @if (Route::has('evaluations'))
                    <flux:sidebar.item icon="document-text" :href="route('evaluations')"
                        :current="request()->routeIs('evaluations')" wire:navigate>
                        {{ __('Evaluations') }}
                    </flux:sidebar.item>
                @endif
                @if (Route::has('subsides'))
                    <flux:sidebar.item icon="building-office-2" :href="route('subsides')"
                        :current="request()->routeIs('subsides')" wire:navigate>
                        {{ __('Subsides') }}
                    </flux:sidebar.item>
                @endif
                @if (Route::has('expenses'))
                    <flux:sidebar.item icon="banknotes" :href="route('expenses')"
                        :current="request()->routeIs('expenses')" wire:navigate>
                        {{ __('Dépenses') }}
                    </flux:sidebar.item>
                @endif
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />

        {{-- <flux:sidebar.nav>
                <flux:sidebar.item icon="zanzibar-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav> --}}
    </flux:sidebar>

    {{ $slot }}

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>
