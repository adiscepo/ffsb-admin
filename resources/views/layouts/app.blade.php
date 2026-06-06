<x-layouts::app.sidebar :title="$title ?? null">
    {{-- C'est ici que tout est chargé, par défaut une page de livewire va utiliser ce layout-ci (il est possible de le changer dans la config cf. https://livewire.laravel.com/docs/4.x/pages#layouts) --}}
    <main class="[grid-area:main] mx-auto w-full h-full max-h-full overflow-y-scroll" data-flux-main>
        @if (isset($header))
            <div class="flex sticky top-0 backdrop-blur-lg">
                {{ $header }}
            </div>
        @endif
        {{ $slot }}
    </main>
</x-layouts::app.sidebar>
