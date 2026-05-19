<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                wire:navigate>
                {{ __('Tableau de bord') }}
            </flux:sidebar.item>
            @if (Route::has('docus'))
                {{-- <flux:sidebar.group expandable="true" :heading="__('Documentaires')" class="grid"> --}}
                <flux:sidebar.item icon="film" :href="route('docus')" :current="request()->routeIs('docus')"
                    wire:navigate>
                    {{ __('Documentaires') }}
                </flux:sidebar.item>
                {{-- </flux:sidebar.group> --}}
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

        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" :avatar="Auth::user()->getProfilePicture()"
                icon-trailing="chevron-down" />
            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()"
                                :avatar="Auth::user()->getProfilePicture()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Paramètres') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Se déconnecter') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>
