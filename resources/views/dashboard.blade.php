<x-layouts::app :title="__('Tableau de bord')" class="relative">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-10">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative rounded-xl border border-neutral-200 dark:border-neutral-700">
                <livewire:widget.last-added-docu />
            </div>
            <div class="relative rounded-xl border border-neutral-200 dark:border-neutral-700">
                <livewire:widget.random-unevaluated />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <livewire:widget.assigned-production-houses />
            </div>
        </div>
        {{-- <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div> --}}
    </div>
    @if (rand(0, 10) % 2 == 0)
        <div class="absolute bottom-2 right-10 w-1/8 rotate-y-180">
            <img src="{{ url('/images/transat.png') }}" class="">
        </div>
    @else
        <div class="absolute bottom-2 right-10 w-1/8">
            <img src="{{ url('/images/Pouf.png') }}" class="">
        </div>
    @endif
    {{-- <div class="absolute top-0 right-0 w-20">
        <img src="{{ url('/images/RideauFurtif.png') }}" class="rotate-y-180">
    </div> --}}
</x-layouts::app>
