<x-layouts::app.sidebar :title="$title ?? null">
    {{-- C'est ici que tout est chargé, par défaut une page de livewire va utiliser ce layout-ci (il est possible de le changer dans la config cf. https://livewire.laravel.com/docs/4.x/pages#layouts) --}}
    @isset($header)
    <header class="w-full" data-flux-header>
        {{ $header }}
    </header>
    @endisset
    <main class="[grid-area:main] mx-auto w-full h-full" data-flux-main>
        {{ $slot }}
    </main>
</x-layouts::app.sidebar>
