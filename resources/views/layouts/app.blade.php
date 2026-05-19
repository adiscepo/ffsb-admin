<x-layouts::app.sidebar :title="$title ?? null">
    {{-- C'est ici que tout est chargé, par défaut une page de livewire va utiliser ce layout-ci (il est possible de le changer dans la config cf. https://livewire.laravel.com/docs/4.x/pages#layouts) --}}
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
